<?php
session_start();
include('../inc/koneksi.php');

$order_id = $_GET['order_id'] ?? '';

if ($order_id == '') {
    die('Order ID tidak ditemukan');
}

// Cari antrean berdasarkan order_id Midtrans
$q = mysqli_query($conn, "
    SELECT id_antrean 
    FROM tblantrean 
    WHERE kode_transaksi_midtrans = '$order_id'
    LIMIT 1
");

if (mysqli_num_rows($q) == 0) {
    die('Data antrean tidak ditemukan');
}

$data = mysqli_fetch_assoc($q);

// Redirect ke halaman cetak antrean
header("Location: konfirmasi_antrean.php?id=" . $data['id_antrean']);
exit;