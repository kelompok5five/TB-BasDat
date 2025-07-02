<?php
include 'koneksi.php';
include 'fungsi_generate.php';

// Tambah pelanggan baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $id_pelanggan = generateID($conn, 'pelanggan', 'id_pelanggan', 'P');
    $nama = $_POST['nama_pelanggan'];
    $nohp = $_POST['kontak'];

    mysqli_query($conn, "INSERT INTO pelanggan (id_pelanggan, nama_pelanggan,kontak) VALUES ('$id_pelanggan', '$nama', '$nohp')")
        or die(mysqli_error($conn));
}

$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Pelanggan</title>
    <style>
        body { background-color: #1c1c1c; font-family: 'Segoe UI', sans-serif; color: #f5f5f5; margin: 0; padding: 0; }
        .navbar { background-color: #2c2c2c; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { margin: 0; font-size: 24px; }
        .menu a { color: #f5f5f5; text-decoration: none; margin-left: 20px; font-weight: bold; }
        .menu a:hover { color: #ffcc00; }
        .container { padding: 30px; }
        .card { background-color: #333; padding: 25px; border-radius: 10px; margin-bottom: 30px; }
        label { display: block; margin-top: 10px; }
        input, textarea { width: 100%; padding: 10px; background-color: #1e1e1e; color: #f5f5f5; border: 1px solid #555; border-radius: 5px; margin-top: 5px; }
        button { background-color: #ffcc00; color: #000; padding: 10px 20px; margin-top: 20px; border: none; font-weight: bold; cursor: pointer; border-radius: 5px; }
        button:hover { background-color: #e6b800; }
        table { width: 100%; border-collapse: collapse; background-color: #2c2c2c; }
        th, td { border: 1px solid #444; padding: 10px; text-align: left; }
        th { background-color: #444; }
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
        <h2>Tambah Pelanggan</h2>
        <form method="POST">
            <label>Nama Pelanggan:</label>
            <input type="text" name="nama_pelanggan" required>

            <label>No HP:</label>
            <input type="text" name="kontak" required>

            <button type="submit" name="tambah">Tambah</button>
        </form>
    </div>

    <div class="card">
        <h2>Daftar Pelanggan</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>No HP</th>
            </tr>
            <?php while ($p = mysqli_fetch_assoc($pelanggan)) { ?>
                <tr>
                    <td><?= $p['id_pelanggan'] ?></td>
                    <td><?= $p['nama_pelanggan'] ?></td>
                    <td><?= $p['kontak']?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
</body>
</html>
