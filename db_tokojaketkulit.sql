-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table db_tokojaketkulit.barang
CREATE TABLE IF NOT EXISTS `barang` (
  `id_barang` varchar(10) NOT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `status` enum('Aktif','Tidak Aktif') DEFAULT NULL,
  `id_supplier` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_barang`),
  KEY `id_supplier` (`id_supplier`),
  CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_tokojaketkulit.barang: ~4 rows (approximately)
INSERT INTO `barang` (`id_barang`, `nama_barang`, `harga`, `stok`, `status`, `id_supplier`) VALUES
	('B1', 'Jaket Kulit', 1200000.00, 13, 'Tidak Aktif', 'S1'),
	('B2', 'Dompet Kulit', 300000.00, 40, 'Aktif', 'S1'),
	('B3', 'Sabuk Kulit', 200000.00, 30, 'Aktif', 'S1'),
	('B4', 'Sendal Tarompah', 500000.00, 25, 'Aktif', 'S1');

-- Dumping structure for table db_tokojaketkulit.detail_transaksi
CREATE TABLE IF NOT EXISTS `detail_transaksi` (
  `id_detail` varchar(10) NOT NULL,
  `id_transaksi` varchar(10) DEFAULT NULL,
  `id_barang` varchar(10) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `id_transaksi` (`id_transaksi`),
  KEY `id_barang` (`id_barang`),
  CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`),
  CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_tokojaketkulit.detail_transaksi: ~19 rows (approximately)
INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_barang`, `jumlah`, `subtotal`) VALUES
	('', 'T009', 'B2', 3, NULL),
	('D1', 'T1', 'B1', 2, 2400000),
	('D12', 'T12', 'B1', 3, 3600000),
	('D2', 'T2', 'B2', 1, 300000),
	('D3', 'T3', 'B3', 3, 600000),
	('D5', 'T2', 'B1', 1, 1200000),
	('D6851b10d7', 'T004', 'B2', 5, 1500000),
	('D6851b1265', 'T004', 'B3', 10, 2000000),
	('D6851bc723', 'T002', 'B1', 3, 3600000),
	('D6851be2e4', 'T003', 'B1', 2, 2400000),
	('D6851be362', 'T003', 'B4', 3, 1500000),
	('D6851beb1d', 'T005', 'B1', 1, 1200000),
	('D6851beb56', 'T005', 'B2', 1, 300000),
	('D6851bfbda', 'T006', 'B2', 2, 600000),
	('D6851bfc2d', 'T006', 'B3', 2, 400000),
	('D6851c215e', 'T007', 'B2', 9, 2700000),
	('D6851c21ab', 'T007', 'B4', 2, 1000000),
	('D6851c2a7c', 'T008', 'B2', 5, 1500000),
	('D6851c2af1', 'T008', 'B3', 5, 1000000),
	('DT001', 'T015', 'B3', 10, NULL),
	('DT002', 'T016', 'B2', 11, NULL),
	('DT003', 'T017', 'B2', 4, NULL),
	('DT004', 'T017', 'B4', 10, NULL),
	('DT005', 'T017', 'B3', 10, NULL),
	('DT006', 'T017', 'B3', 10, NULL),
	('DT007', 'T017', 'B2', 20, NULL),
	('DT008', 'T017', 'B4', 30, NULL);

-- Dumping structure for procedure db_tokojaketkulit.HitungSubtotalPerDetail
DELIMITER //
CREATE PROCEDURE `HitungSubtotalPerDetail`(
    IN p_id_detail VARCHAR(10)
)
BEGIN
    SELECT 
        d.id_detail,
        d.id_transaksi,
        b.nama_barang,
        d.jumlah,
        b.harga,
        (d.jumlah * b.harga) AS subtotal
    FROM Detail_Transaksi d
    JOIN Barang b ON d.id_barang = b.id_barang
    WHERE d.id_detail = p_id_detail;
END//
DELIMITER ;

-- Dumping structure for table db_tokojaketkulit.karyawan
CREATE TABLE IF NOT EXISTS `karyawan` (
  `id_karyawan` varchar(10) NOT NULL,
  `nama_karyawan` varchar(100) DEFAULT NULL,
  `divisi` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_karyawan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_tokojaketkulit.karyawan: ~2 rows (approximately)
INSERT INTO `karyawan` (`id_karyawan`, `nama_karyawan`, `divisi`) VALUES
	('K1', 'Jalal', 'Penjualan, Pengrajin'),
	('K2', 'Rudi', 'Penjualan, Pengrajin');

-- Dumping structure for table db_tokojaketkulit.pelanggan
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id_pelanggan` varchar(10) NOT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `kontak` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_tokojaketkulit.pelanggan: ~9 rows (approximately)
INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `kontak`) VALUES
	('C1', 'Ibu Teti', '0812345678'),
	('C2', 'Bapak Dedi', '0822334455'),
	('C3', 'Andi Pratama', '081234567890'),
	('P001', 'Usep', '08123456788'),
	('P002', 'Hanif', '08123654799'),
	('P003', 'Iam', '0897899987'),
	('P004', 'Cakra', '0897867767'),
	('P005', 'Pikuk', '08976545412'),
	('P006', 'Igot', '08978675645'),
	('P007', 'Ajip', '0897776677');

-- Dumping structure for table db_tokojaketkulit.supplier
CREATE TABLE IF NOT EXISTS `supplier` (
  `id_supplier` varchar(10) NOT NULL,
  `nama_supplier` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_tokojaketkulit.supplier: ~1 rows (approximately)
INSERT INTO `supplier` (`id_supplier`, `nama_supplier`) VALUES
	('S1', 'Owner');

-- Dumping structure for procedure db_tokojaketkulit.TambahTransaksiDanDetail
DELIMITER //
CREATE PROCEDURE `TambahTransaksiDanDetail`(
    IN p_id_transaksi VARCHAR(10),
    IN p_tanggal DATE,
    IN p_id_pelanggan VARCHAR(10),
    IN p_tipe_transaksi ENUM('Pre-Order', 'Offline'),
    IN p_uang_muka INT,
    IN p_status_produksi ENUM('Tersedia', 'Dalam Produksi'),
    IN p_tanggal_pelunasan DATE,
    IN p_id_karyawan VARCHAR(10),
    
    IN p_id_detail VARCHAR(10),
    IN p_id_barang VARCHAR(10),
    IN p_jumlah INT
)
BEGIN
    DECLARE v_harga INT;
    DECLARE v_stok INT;
    DECLARE v_subtotal INT;

    -- Ambil stok dan harga dari barang
    SELECT stok, harga INTO v_stok, v_harga
    FROM barang
    WHERE id_barang = p_id_barang;

    -- Cek stok
    IF v_stok < p_jumlah THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Stok tidak mencukupi untuk transaksi ini.';
    ELSE
        -- Hitung subtotal
        SET v_subtotal = p_jumlah * v_harga;

        -- Tambah transaksi
        INSERT INTO transaksi (
            id_transaksi, tanggal, id_pelanggan, tipe_transaksi, uang_muka,
            status_produksi, tanggal_pelunasan, id_karyawan
        ) VALUES (
            p_id_transaksi, p_tanggal, p_id_pelanggan, p_tipe_transaksi,
            p_uang_muka, p_status_produksi, p_tanggal_pelunasan, p_id_karyawan
        );

        -- Tambah detail transaksi
        INSERT INTO detail_transaksi (
            id_detail, id_transaksi, id_barang, jumlah, subtotal
        ) VALUES (
            p_id_detail, p_id_transaksi, p_id_barang, p_jumlah, v_subtotal
        );

        -- Kurangi stok
        UPDATE barang
        SET stok = stok - p_jumlah
        WHERE id_barang = p_id_barang;
    END IF;
END//
DELIMITER ;

-- Dumping structure for table db_tokojaketkulit.transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id_transaksi` varchar(10) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `id_pelanggan` varchar(10) DEFAULT NULL,
  `tipe_transaksi` enum('Pre-Order','Offline') DEFAULT NULL,
  `uang_muka` int(11) DEFAULT NULL,
  `status_produksi` enum('Tersedia','Dalam Produksi') DEFAULT NULL,
  `tanggal_pelunasan` date DEFAULT NULL,
  `id_karyawan` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `id_pelanggan` (`id_pelanggan`),
  KEY `id_karyawan` (`id_karyawan`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`),
  CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_tokojaketkulit.transaksi: ~19 rows (approximately)
INSERT INTO `transaksi` (`id_transaksi`, `tanggal`, `id_pelanggan`, `tipe_transaksi`, `uang_muka`, `status_produksi`, `tanggal_pelunasan`, `id_karyawan`) VALUES
	('T001', '2025-06-18', 'C2', 'Offline', 1000000, 'Tersedia', '2025-06-18', 'K1'),
	('T002', '2025-06-18', 'C2', 'Offline', 1000000, 'Dalam Produksi', '2025-06-25', 'K2'),
	('T003', '2025-06-20', 'P002', 'Offline', 100000, 'Tersedia', '2025-06-20', 'K2'),
	('T004', '2025-06-18', 'C1', 'Offline', 1000000, 'Dalam Produksi', '2025-06-18', 'K1'),
	('T005', '2025-06-20', 'P001', 'Pre-Order', 100000, 'Dalam Produksi', '2025-06-20', 'K2'),
	('T006', '2025-06-21', 'P003', 'Pre-Order', 1000000, 'Dalam Produksi', '2025-06-25', 'K2'),
	('T007', '2025-06-19', 'P004', 'Pre-Order', 1000000, 'Dalam Produksi', '2025-06-25', 'K2'),
	('T008', '2025-06-19', 'P005', 'Pre-Order', 1500000, 'Dalam Produksi', '2025-06-25', 'K2'),
	('T009', '2025-06-19', 'P006', 'Offline', 1500000, 'Tersedia', '2025-06-19', 'K2'),
	('T010', '2025-06-19', 'P004', 'Offline', 1500000, 'Dalam Produksi', '2025-06-19', 'K2'),
	('T011', '2025-06-19', 'C2', 'Offline', 1500000, 'Tersedia', '2025-06-19', 'K2'),
	('T012', '2025-06-19', 'C2', 'Offline', 1000000, 'Tersedia', '2025-06-19', 'K2'),
	('T013', '2025-06-18', 'C3', 'Pre-Order', 1500000, 'Dalam Produksi', '2025-06-25', 'K2'),
	('T014', '2025-06-18', 'C3', 'Pre-Order', 1500000, 'Dalam Produksi', '2025-06-25', 'K2'),
	('T015', '2025-06-19', 'P005', 'Pre-Order', 100000, 'Dalam Produksi', '2025-06-25', 'K2'),
	('T016', '2025-06-19', 'P005', 'Pre-Order', 100000, 'Dalam Produksi', '2025-06-25', 'K2'),
	('T017', '2025-06-18', 'P007', 'Pre-Order', 20000000, 'Dalam Produksi', '2025-06-25', 'K2'),
	('T1', '2024-12-13', 'C1', 'Pre-Order', 500000, 'Dalam Produksi', NULL, 'K1'),
	('T12', '2025-06-18', 'C3', 'Pre-Order', 300000, 'Dalam Produksi', NULL, 'K2'),
	('T2', '2024-12-14', 'C2', 'Offline', 600000, 'Tersedia', NULL, 'K2'),
	('T3', '2024-12-15', 'C1', 'Offline', 300000, 'Dalam Produksi', NULL, 'K1');

-- Dumping structure for trigger db_tokojaketkulit.cek_stok_sebelum_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER cek_stok_sebelum_insert
BEFORE INSERT ON Detail_Transaksi
FOR EACH ROW
BEGIN
    DECLARE stok_sekarang INT;

    -- Ambil stok saat ini dari tabel Barang
    SELECT stok INTO stok_sekarang
    FROM Barang
    WHERE id_barang = NEW.id_barang;

    -- Jika stok tidak cukup, batalkan insert
    IF stok_sekarang < NEW.jumlah THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Stok barang tidak mencukupi untuk transaksi ini.';
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger db_tokojaketkulit.kurangi_stok_setelah_transaksi
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER kurangi_stok_setelah_transaksi
AFTER INSERT ON Detail_Transaksi
FOR EACH ROW
BEGIN
    UPDATE Barang
    SET stok = stok - NEW.jumlah
    WHERE id_barang = NEW.id_barang;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
