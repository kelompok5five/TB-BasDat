<?php
include 'koneksi.php';
include 'koneksi.php';
include 'fungsi_generate.php'; 

$id_barang_baru = generateID($conn, 'barang', 'id_barang', 'B');  

// Hapus (nonaktifkan) barang
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    $cek_relasi = mysqli_query($conn, "SELECT COUNT(*) AS total FROM detail_transaksi WHERE id_barang = '$id_hapus'");
    $cek = mysqli_fetch_assoc($cek_relasi);

    if ($cek['total'] == 0) {
        mysqli_query($conn, "DELETE FROM barang WHERE id_barang = '$id_hapus'") or die("Gagal menghapus: " . mysqli_error($conn));
    } else {
        mysqli_query($conn, "UPDATE barang SET status='Tidak Aktif' WHERE id_barang = '$id_hapus'");
        echo "<script>alert('Barang telah dinonaktifkan karena sudah digunakan dalam transaksi.');</script>";
    }
    header('Location: barang.php');
    exit();
}

// Update barang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $id_barang = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $id_supplier = $_POST['id_supplier'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE barang SET nama_barang='$nama_barang', id_supplier='$id_supplier', harga=$harga, stok=$stok, status='$status' WHERE id_barang='$id_barang'");
    header('Location: barang.php');
    exit();
}

// Simpan barang baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $id_barang = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $id_supplier = $_POST['id_supplier'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    mysqli_query($conn, "INSERT INTO barang (id_barang, nama_barang, id_supplier, harga, stok, status) VALUES ('$id_barang', '$nama_barang', '$id_supplier', $harga, $stok, 'Aktif')");
}

$supplier = mysqli_query($conn, "SELECT * FROM supplier");
$barang = mysqli_query($conn, "SELECT b.*, s.nama_supplier AS supplier FROM barang b JOIN supplier s ON b.id_supplier = s.id_supplier") or die(mysqli_error($conn));

$edit_mode = false;
$data_edit = null;
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id_edit = $_GET['edit'];
    $data = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang = '$id_edit'");
    $data_edit = mysqli_fetch_assoc($data);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Barang</title>
    <style>
        body { background-color: #1c1c1c; font-family: 'Segoe UI', sans-serif; color: #f5f5f5; margin: 0; padding: 0; }
        .navbar { background-color: #2c2c2c; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
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
        .actions a { color: #ffcc00; margin-right: 10px; text-decoration: none; font-weight: bold; }
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
        <h2><?= $edit_mode ? 'Edit Barang' : 'Tambah Barang' ?></h2>
        <form method="POST">
            <label>ID Barang:</label>
            <input type="text" name="id_barang" value="<?= $data_edit['id_barang'] ?? '' ?>" <?= $edit_mode ? 'readonly' : '' ?> required>

            <label>Nama Barang:</label>
            <input type="text" name="nama_barang" value="<?= $data_edit['nama_barang'] ?? '' ?>" required>

            <label>Supplier:</label>
            <select name="id_supplier" required>
                <option value="">-- Pilih Supplier --</option>
                <?php while ($s = mysqli_fetch_assoc($supplier)) { ?>
                    <option value="<?= $s['id_supplier'] ?>" <?= ($edit_mode && $s['id_supplier'] == $data_edit['id_supplier']) ? 'selected' : '' ?>>
                        <?= $s['nama_supplier'] ?>
                    </option>
                <?php } mysqli_data_seek($supplier, 0); ?>
            </select>

            <label>Harga:</label>
            <input type="number" name="harga" value="<?= $data_edit['harga'] ?? '' ?>" required>

            <label>Stok:</label>
            <input type="number" name="stok" value="<?= $data_edit['stok'] ?? '' ?>" required>

            <?php if ($edit_mode) { ?>
                <label>Status:</label>
                <select name="status" required>
                    <option value="Aktif" <?= $data_edit['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="Tidak Aktif" <?= $data_edit['status'] === 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
            <?php } ?>

            <button type="submit" name="<?= $edit_mode ? 'edit' : 'tambah' ?>">
                <?= $edit_mode ? 'Simpan Perubahan' : 'Tambah Barang' ?>
            </button>
        </form>
    </div>

    <div class="card">
        <h2>Daftar Barang</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Supplier</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php while ($b = mysqli_fetch_assoc($barang)) { ?>
                <tr>
                    <td><?= $b['id_barang'] ?></td>
                    <td><?= $b['nama_barang'] ?></td>
                    <td><?= $b['supplier'] ?></td>
                    <td>Rp<?= number_format($b['harga'], 0, ',', '.') ?></td>
                    <td><?= $b['stok'] ?></td>
                    <td><?= $b['status'] ?></td>
                    <td class="actions">
                        <a href="barang.php?edit=<?= $b['id_barang'] ?>">Edit</a>
                        <a href="barang.php?hapus=<?= $b['id_barang'] ?>" onclick="return confirm('Yakin ingin menghapus atau menonaktifkan barang ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
</body>
</html>
