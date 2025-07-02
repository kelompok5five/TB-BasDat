<?php
include 'koneksi.php';

if (!isset($_GET['id_transaksi'])) {
    die('ID transaksi tidak ditemukan.');
}

$id = $_GET['id_transaksi'];

// Ambil data transaksi dan pelanggan
$transaksi = mysqli_query($conn, "
    SELECT t.*, p.nama_pelanggan,p.kontak, k.nama_karyawan
    FROM transaksi t
    JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
    JOIN karyawan k ON t.id_karyawan = k.id_karyawan
    WHERE id_transaksi = '$id'
");
$data_transaksi = mysqli_fetch_assoc($transaksi);

// Ambil detail barang
$detail = mysqli_query($conn, "
    SELECT dt.*, b.nama_barang, b.harga
    FROM detail_transaksi dt
    JOIN barang b ON dt.id_barang = b.id_barang
    WHERE dt.id_transaksi = '$id'
");

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Transaksi</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #fff;
            color: #000;
            padding: 30px;
        }
        h2, h3 { text-align: center; }
        .info, .footer { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px dashed #000; padding: 8px; text-align: left; }
        .total { font-weight: bold; text-align: right; }
        .footer { text-align: center; font-size: 12px; margin-top: 40px; }
        .print { margin-top: 20px; text-align: center; }
        .print button { padding: 10px 20px; font-weight: bold; }
    </style>
</head>
<body>

<h2>Toko Jaket Kulit</h2>
<h3>Struk Transaksi</h3>

<div class="info">
    <p><strong>ID Transaksi:</strong> <?= $data_transaksi['id_transaksi'] ?></p>
    <p><strong>Tanggal:</strong> <?= $data_transaksi['tanggal'] ?></p>
    <p><strong>Pelanggan:</strong> <?= $data_transaksi['nama_pelanggan'] ?> | <?= $data_transaksi['kontak'] ?></p>
    <p><strong>Karyawan:</strong> <?= $data_transaksi['nama_karyawan'] ?></p>
    <p><strong>Tipe:</strong> <?= $data_transaksi['tipe_transaksi'] ?> | <strong>Status:</strong> <?= $data_transaksi['status_produksi'] ?></p>
</div>

<table>
    <tr>
        <th>Barang</th>
        <th>Qty</th>
        <th>Harga</th>
        <th>Subtotal</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($detail)) {
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
        <td colspan="3" class="total">Total</td>
        <td class="total">Rp<?= number_format($total, 0, ',', '.') ?></td>
    </tr>
</table>

<div class="footer">
    <p>Terima kasih telah berbelanja di Toko Jaket Kulit</p>
    <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
</div>

<div class="print">
    <button onclick="window.print()">Cetak Struk</button>
</div>

</body>
</html>
