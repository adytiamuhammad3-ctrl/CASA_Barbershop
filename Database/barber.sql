-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
-- Host: 127.0.0.1
-- Generation Time: May 29, 2023 at 05:31 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;

-- ======================================================
-- Database: barber
-- ======================================================

-- ------------------------------------------------------
-- Table: admin
-- ------------------------------------------------------
CREATE TABLE admin (
    id INT(11) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

INSERT INTO admin (id, username, password) VALUES
(1, 'admin', '7b374aa0e3db4efd82c91d152d9f5391');

ALTER TABLE admin
    ADD PRIMARY KEY (id);

ALTER TABLE admin
    MODIFY id INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- ------------------------------------------------------
-- Table: tblinvoice
-- ------------------------------------------------------
CREATE TABLE tblinvoice (
    id INT(11) NOT NULL,
    Userid INT(11) DEFAULT NULL,
    ServiceId INT(11) DEFAULT NULL,
    BillingId INT(11) DEFAULT NULL,
    PostingDate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP()
) ENGINE=InnoDB
DEFAULT CHARSET=latin1
COLLATE=latin1_swedish_ci;

INSERT INTO tblinvoice (id, Userid, ServiceId, BillingId, PostingDate) VALUES
(33, 42, 23, 350495011, '2023-05-29 02:32:14');

ALTER TABLE tblinvoice
    ADD PRIMARY KEY (id);

ALTER TABLE tblinvoice
    MODIFY id INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

-- ------------------------------------------------------
-- Table: tblpage
-- ------------------------------------------------------
CREATE TABLE tblpage (
    ID INT(10) NOT NULL,
    PageType VARCHAR(200) DEFAULT NULL,
    PageTitle MEDIUMTEXT DEFAULT NULL,
    PageDescription MEDIUMTEXT DEFAULT NULL
) ENGINE=InnoDB
DEFAULT CHARSET=latin1
COLLATE=latin1_swedish_ci;

INSERT INTO tblpage (ID, PageType, PageTitle, PageDescription) VALUES
(
    1,
    'aboutus',
    'About Us',
    'kami adalah Barbershop yang telah berdiri sejak tahun 2004...'
),
(
    3,
    'location',
    'Lokasi Barber',
    '<iframe src="https://www.google.com/maps/embed?..."></iframe>'
);

ALTER TABLE tblpage
    ADD PRIMARY KEY (ID);

ALTER TABLE tblpage
    MODIFY ID INT(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- ------------------------------------------------------
-- Table: tblservices
-- ------------------------------------------------------
CREATE TABLE tblservices (
    ID INT(10) NOT NULL,
    ServiceName VARCHAR(200) DEFAULT NULL,
    ServiceDescription MEDIUMTEXT DEFAULT NULL,
    Cost INT(10) DEFAULT NULL,
    Image VARCHAR(200) DEFAULT NULL,
    CreationDate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP()
) ENGINE=InnoDB
DEFAULT CHARSET=latin1
COLLATE=latin1_swedish_ci;

INSERT INTO tblservices
(ID, ServiceName, ServiceDescription, Cost, Image, CreationDate)
VALUES
(23, 'Buzz Cut', 'potongan', 15, 'buzz.jpg', '2023-05-13 00:42:03'),
(25, 'Two Block', 'potongan pendek', 15, 'two.jpg', '2023-05-13 01:17:40'),
(26, 'Mid Fade', 'masbroo', 15, 'mid.jpg', '2023-05-13 01:23:03'),
(27, 'Taper Fade', 'hahahihi', 15, 'taper.jpg', '2023-05-13 01:28:07'),
(28, 'Low Fade', 'pendek pendek', 15, 'low.jpg', '2023-05-13 01:31:46'),
(29, 'French Crop', 'huhuh', 15, 'crop.jpg', '2023-05-13 01:33:26');

ALTER TABLE tblservices
    ADD PRIMARY KEY (ID);

ALTER TABLE tblservices
    MODIFY ID INT(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

-- ------------------------------------------------------
-- Table: users
-- ------------------------------------------------------
CREATE TABLE users (
    id INT(11) NOT NULL,
    nama VARCHAR(255) NOT NULL,
    number BIGINT(14) DEFAULT NULL,
    email VARCHAR(255) NOT NULL,
    password TEXT NOT NULL,
    RegDate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
    verif_code TEXT NOT NULL,
    reset_code VARCHAR(100) DEFAULT NULL,
    is_verified TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB
DEFAULT CHARSET=latin1
COLLATE=latin1_swedish_ci;

INSERT INTO users
(id, nama, number, email, password, RegDate, verif_code, reset_code, is_verified)
VALUES
(
    42,
    'Baden Nugraha',
    8912345,
    'badennugraha4@gmail.com',
    '$2y$10$NrpPSFVnjjNzj8aRLUInY.EeunB0Cd3pH/MjpJvOsU8OklfJYI2fS',
    '2023-05-25 10:16:15',
    'c984f2af4d9652f88dfb4bc5f705796b',
    NULL,
    1
);

ALTER TABLE users
    ADD PRIMARY KEY (id);

ALTER TABLE users
    MODIFY id INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

-- ------------------------------------------------------
-- Table: tblantrean (Antrean / Pemesanan)
-- ------------------------------------------------------
CREATE TABLE tblantrean (
   id_antrean INT(11) NOT NULL,
    Userid INT(11) DEFAULT NULL,
    nama_pelanggan VARCHAR(255) NOT NULL,
    ServiceId INT(11) NOT NULL,
    nomor_antrean VARCHAR(10) NOT NULL,
    tanggal_antrean DATE NOT NULL,
    total_biaya INT(11) NOT NULL,
    metode_pembayaran ENUM('Cash', 'Midtrans') NOT NULL,
    status_pembayaran ENUM('Pending', 'Paid', 'Failed') NOT NULL DEFAULT 'Pending',
    status_antrean ENUM('Menunggu', 'Diproses', 'Selesai', 'Batal') NOT NULL DEFAULT 'Menunggu',
    kode_transaksi_midtrans VARCHAR(100) DEFAULT NULL,
    waktu_pesan TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP()
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

ALTER TABLE tblantrean
    ADD PRIMARY KEY (id_antrean),
    ADD KEY Userid (Userid),
    ADD KEY ServiceId (ServiceId);

ALTER TABLE tblantrean
    MODIFY id_antrean INT(11) NOT NULL AUTO_INCREMENT;

-- Foreign Key (Opsional)
ALTER TABLE tblantrean
    ADD CONSTRAINT fk_antrean_user
        FOREIGN KEY (Userid) REFERENCES users (id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    ADD CONSTRAINT fk_antrean_service
        FOREIGN KEY (ServiceId) REFERENCES tblservices (ID)
        ON DELETE RESTRICT ON UPDATE CASCADE;

COMMIT;