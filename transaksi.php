<?php
include 'koneksi.php';
include 'fungsi_generate.php';

$id_transaksi_baru = generateID($conn, 'transaksi', 'id_transaksi', 'T');
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");
$karyawan = mysqli_query($conn, "SELECT * FROM karyawan");
$transaksi = mysqli_query($conn, "
    SELECT t.*, p.nama_pelanggan
    FROM transaksi t
    JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
    ORDER BY t.tanggal DESC
");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_transaksi = $id_transaksi_baru;
    $tanggal = $_POST['tanggal'];
    $id_pelanggan = $_POST['id_pelanggan'];
    $tipe_transaksi = $_POST['tipe_transaksi'];
    $uang_muka = $_POST['uang_muka'];
    $status_produksi = $_POST['status_produksi'];
    $tanggal_pelunasan = $_POST['tanggal_pelunasan'];
    $id_karyawan = $_POST['id_karyawan'];

    $tanggal_pelunasan_sql = !empty($tanggal_pelunasan) ? "'$tanggal_pelunasan'" : "NULL";

    $simpan = mysqli_query($conn, "
        INSERT INTO transaksi (
            id_transaksi, tanggal, id_pelanggan, tipe_transaksi,
            uang_muka, status_produksi, tanggal_pelunasan, id_karyawan
        ) VALUES (
            '$id_transaksi', '$tanggal', '$id_pelanggan', '$tipe_transaksi',
            $uang_muka, '$status_produksi', $tanggal_pelunasan_sql, '$id_karyawan'
        )
    ");

    if ($simpan) {
        header("Location: tambah_detail.php?id_transaksi=$id_transaksi");
        exit();
    } else {
        echo "Gagal menyimpan transaksi: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Transaksi</title>
    <style>
        body {
            background-color: #1c1c1c;
            font-family: 'Segoe UI', sans-serif;
            color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #2c2c2c;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 { margin: 0; font-size: 24px; }
        .menu a { color: #f5f5f5; text-decoration: none; margin-left: 20px; font-weight: bold; }
        .menu a:hover { color: #ffcc00; }
        .container { padding: 30px; }
        .card { background-color: #333; padding: 25px; border-radius: 10px; margin-bottom: 30px; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 10px; background-color: #1e1e1e; color: #f5f5f5; border: 1px solid #555; border-radius: 5px; margin-top: 5px; }
        button { background-color: #ffcc00; color: #000; padding: 10px 20px; margin-top: 20px; border: none; font-weight: bold; cursor: pointer; border-radius: 5px; }
        button:hover { background-color: #e6b800; }
        table { width: 100%; border-collapse: collapse; background-color: #2c2c2c; }
        th, td { border: 1px solid #444; padding: 10px; text-align: left; }
        th { background-color: #444; }
        a.struk-link { color: #ffcc00; text-decoration: none; font-weight: bold; }
        a.struk-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Toko Jaket Kulit</h1>
    <div class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="barang.php">Barang</a>
        <a href="transaksi.php">Transaksi</a>
        <a href="pelanggan.php">Pelanggan</a>
    </div>
</div>

<div class="container">
    <div class="card">
        <h2>Tambah Transaksi Baru</h2>
        <form method="POST">
            <input type="hidden" name="id_transaksi" value="<?= $id_transaksi_baru ?>">

            <label>Tanggal:</label>
            <input type="date" name="tanggal" required>

            <label>Pelanggan:</label>
            <select name="id_pelanggan" required>
                <option value="">-- Pilih Pelanggan --</option>
                <?php while ($p = mysqli_fetch_assoc($pelanggan)) { ?>
                    <option value="<?= $p['id_pelanggan'] ?>"><?= $p['nama_pelanggan'] ?></option>
                <?php } ?>
            </select>

            <label>Tipe Transaksi:</label>
            <select name="tipe_transaksi" required>
                <option value="Pre-Order">Pre-Order</option>
                <option value="Offline">Offline</option>
            </select>

            <label>Uang Muka:</label>
            <input type="number" name="uang_muka" required>

            <label>Status Produksi:</label>
            <select name="status_produksi" required>
                <option value="Dalam Produksi">Dalam Produksi</option>
                <option value="Tersedia">Tersedia</option>
            </select>

            <label>Tanggal Pelunasan (Opsional):</label>
            <input type="date" name="tanggal_pelunasan">

            <label>Karyawan Penanggung Jawab:</label>
            <select name="id_karyawan" required>
                <option value="">-- Pilih Karyawan --</option>
                <?php while ($k = mysqli_fetch_assoc($karyawan)) { ?>
                    <option value="<?= $k['id_karyawan'] ?>"><?= $k['nama_karyawan'] ?></option>
                <?php } ?>
            </select>

            <button type="submit">Lanjut Tambah Detail Barang</button>
        </form>
    </div>

    <div class="card">
        <h2>Riwayat Transaksi</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php while ($t = mysqli_fetch_assoc($transaksi)) { ?>
                <tr>
                    <td><?= $t['id_transaksi'] ?></td>
                    <td><?= $t['tanggal'] ?></td>
                    <td><?= $t['nama_pelanggan'] ?></td>
                    <td><?= $t['tipe_transaksi'] ?></td>
                    <td><?= $t['status_produksi'] ?></td>
                    <td>
                        <a class="struk-link" href="struk.php?id_transaksi=<?= $t['id_transaksi'] ?>" target="_blank">🧾 Struk</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
</body>
</html>
