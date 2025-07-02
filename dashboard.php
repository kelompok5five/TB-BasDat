<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Toko Jaket Kulit</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #1c1c1c;
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

        .navbar h1 {
            margin: 0;
            font-size: 24px;
        }

        .menu a {
            color: #f5f5f5;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }

        .menu a:hover {
            color: #ffcc00;
        }

        .container {
            padding: 30px;
        }

        .card {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .card h3 {
            margin-top: 0;
            color: #ffcc00;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #444;
            text-align: left;
        }

        th {
            background-color: #444;
        }
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

    <!-- Ringkasan -->
    <div class="card">
        <h3>Ringkasan Data</h3>
        <?php
        $barang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM barang"));
        $pelanggan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggan"));
        $transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi"));
        ?>
        <p>Total Barang: <strong><?= $barang['total'] ?></strong></p>
        <p>Total Pelanggan: <strong><?= $pelanggan['total'] ?></strong></p>
        <p>Total Transaksi: <strong><?= $transaksi['total'] ?></strong></p>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="card">
        <h3>Transaksi Terbaru</h3>
        <table>
            <tr>
                <th>ID Transaksi</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
            <?php
            $query = mysqli_query($conn, "
                SELECT 
                    t.id_transaksi,
                    t.tanggal,
                    p.nama_pelanggan,
                    t.tipe_transaksi,
                    t.status_produksi,
                    SUM(dt.subtotal) AS total_harga
                FROM transaksi t
                LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                LEFT JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
                GROUP BY t.id_transaksi
                ORDER BY t.tanggal DESC
                LIMIT 5
            ");

            if (!$query) {
                die('Query Error: ' . mysqli_error($conn));
            }

            while ($row = mysqli_fetch_assoc($query)) {
                echo "<tr>
                    <td>{$row['id_transaksi']}</td>
                    <td>{$row['tanggal']}</td>
                    <td>{$row['nama_pelanggan']}</td>
                    <td>{$row['tipe_transaksi']}</td>
                    <td>{$row['status_produksi']}</td>
                    <td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>
                </tr>";
            }
            ?>
        </table>
    </div>

</div>

</body>
</html>