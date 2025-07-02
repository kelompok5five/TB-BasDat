<?php
include 'koneksi.php';
include 'fungsi_generate.php';

if (!isset($_GET['id_transaksi'])) {
    die('ID transaksi tidak ditemukan.');
}

$id_transaksi = $_GET['id_transaksi'];

// Tampilkan hanya barang yang aktif
$barang = mysqli_query($conn, "SELECT * FROM barang WHERE status = 'Aktif'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_barang = $_POST['id_barang'];
    $jumlah = intval($_POST['jumlah']);

    echo "<pre>DEBUG:\n";
    var_dump([
        "id_transaksi" => $id_transaksi,
        "id_barang" => $id_barang,
        "jumlah" => $jumlah
    ]);
    echo "</pre>";

    if ($jumlah <= 0) {
        echo "<script>alert('Jumlah tidak valid.');</script>";
    } else {
        $cek = mysqli_query($conn, "SELECT stok FROM barang WHERE id_barang = '$id_barang'");
        $stok_row = mysqli_fetch_assoc($cek);

        if (!$stok_row) {
            echo "<script>alert('Barang tidak ditemukan.');</script>";
        } else {
            $stok = $stok_row['stok'];

            if ($jumlah > $stok) {
                echo "<script>alert('Stok tidak mencukupi!');</script>";
            } else {
                // Generate ID detail_transaksi
                $id_detail = generateID($conn, 'detail_transaksi', 'id_detail', 'DT');

                $query_insert = "INSERT INTO detail_transaksi (id_detail, id_transaksi, id_barang, jumlah)
                                 VALUES ('$id_detail', '$id_transaksi', '$id_barang', $jumlah)";
                $result_insert = mysqli_query($conn, $query_insert);

                if ($result_insert) {
                    mysqli_query($conn, "
                        UPDATE barang SET stok = stok - $jumlah WHERE id_barang = '$id_barang'
                    ");
                    header("Location: tambah_detail.php?id_transaksi=$id_transaksi");
                    exit();
                } else {
                    echo "<pre>QUERY FAILED: $query_insert\n" . mysqli_error($conn) . "</pre>";
                    echo "<script>alert('Gagal menyimpan detail. Cek log.');</script>";
                }
            }
        }
    }
}

// Ambil ulang detail transaksi setelah proses POST
$detail = mysqli_query($conn, "
    SELECT dt.*, b.nama_barang, b.harga
    FROM detail_transaksi dt
    JOIN barang b ON dt.id_barang = b.id_barang
    WHERE dt.id_transaksi = '$id_transaksi'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Transaksi</title>
    <style>
        body { background-color: #1c1c1c; font-family: 'Segoe UI', sans-serif; color: #f5f5f5; margin: 0; padding: 30px; }
        .card { background: #333; padding: 20px; border-radius: 10px; max-width: 700px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 10px; background-color: #1e1e1e; color: #f5f5f5; border: 1px solid #555; border-radius: 5px; margin-top: 5px; }
        button { background-color: #ffcc00; color: #000; padding: 10px 20px; margin-top: 20px; border: none; font-weight: bold; cursor: pointer; border-radius: 5px; }
        button:hover { background-color: #e6b800; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #2c2c2c; }
        th, td { border: 1px solid #444; padding: 10px; }
        th { background-color: #444; }
    </style>
</head>
<body>

<div class="card">
    <h2>Tambah Barang ke Transaksi #<?= $id_transaksi ?></h2>
    <form method="POST">
        <label>Barang:</label>
        <select name="id_barang" required>
            <option value="">-- Pilih Barang --</option>
            <?php while ($b = mysqli_fetch_assoc($barang)) { ?>
                <option value="<?= $b['id_barang'] ?>">
                    <?= $b['nama_barang'] ?> - Rp<?= number_format($b['harga'], 0, ',', '.') ?> (Stok: <?= $b['stok'] ?>)
                </option>
            <?php } ?>
        </select>

        <label>Jumlah:</label>
        <input type="number" name="jumlah" min="1" required>

        <button type="submit">Tambah</button>
    </form>

    <h3>Barang yang Ditambahkan</h3>
    <table>
        <tr>
            <th>Barang</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
        <?php
        $total = 0;
        while ($row = mysqli_fetch_assoc($detail)) {
            $subtotal = $row['jumlah'] * $row['harga'];
            $total += $subtotal;
        ?>
        <tr>
            <td><?= $row['nama_barang'] ?></td>
            <td><?= $row['jumlah'] ?></td>
            <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
            <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="3" style="text-align:right"><strong>Total Harga:</strong></td>
            <td><strong>Rp<?= number_format($total, 0, ',', '.') ?></strong></td>
        </tr>
    </table>

    <?php
    $muka_q = mysqli_query($conn, "SELECT uang_muka FROM transaksi WHERE id_transaksi = '$id_transaksi'");
    $uang_muka = mysqli_fetch_assoc($muka_q)['uang_muka'] ?? 0;
    $kembalian = $uang_muka - $total;
    ?>

    <h4>Uang Muka: <span style="color:#ffcc00">Rp<?= number_format($uang_muka, 0, ',', '.') ?></span></h4>
    <h4>Kembalian: <span style="color:#ffcc00">Rp<?= number_format($kembalian, 0, ',', '.') ?></span></h4>

    <a href="struk.php?id_transaksi=<?= $id_transaksi ?>" target="_blank">
        <button>Cetak Struk</button>
    </a>
</div>

</body>
</html>
