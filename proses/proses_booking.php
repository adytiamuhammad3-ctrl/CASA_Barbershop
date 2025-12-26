<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../inc/koneksi.php');
require_once('../Midtrans/Midtrans.php');

date_default_timezone_set('Asia/Jakarta');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit;
}

/* ================= VALIDASI INPUT ================= */
$nama_pelanggan   = mysqli_real_escape_string($conn, $_POST['nama_pelanggan']);
$service_id       = (int) $_POST['service_id'];
$metode_pembayaran = $_POST['metode_pembayaran'];
$tanggal_antrean  = date('Y-m-d');

if (!$nama_pelanggan || !$service_id || !$metode_pembayaran) {
    header("Location: ../user/booking.php?error=Data tidak lengkap");
    exit;
}

/* ================= AMBIL DATA LAYANAN ================= */
$qService = mysqli_query($conn, "SELECT Cost, ServiceName FROM tblservices WHERE ID='$service_id'");
if (mysqli_num_rows($qService) === 0) {
    header("Location: ../user/booking.php?error=Layanan tidak valid");
    exit;
}

$service = mysqli_fetch_assoc($qService);
$total_biaya = $service['Cost'] * 1000;

/* ================= NOMOR ANTREAN ================= */
$qLast = mysqli_query($conn, "
    SELECT nomor_antrean 
    FROM tblantrean 
    WHERE tanggal_antrean='$tanggal_antrean' 
    ORDER BY id_antrean DESC LIMIT 1
");

$next = 1;
if ($row = mysqli_fetch_assoc($qLast)) {
    $next = ((int) substr($row['nomor_antrean'], 1)) + 1;
}
$nomor_antrean = 'A' . str_pad($next, 2, '0', STR_PAD_LEFT);

/* ================= ORDER ID MIDTRANS ================= */
$order_id = 'TRX-' . time();

/* ================= CASH ================= */
if ($metode_pembayaran === 'Cash') {

    mysqli_query($conn, "
        INSERT INTO tblantrean (
            nama_pelanggan, ServiceId, nomor_antrean, tanggal_antrean,
            total_biaya, metode_pembayaran, status_pembayaran, status_antrean
        ) VALUES (
            '$nama_pelanggan','$service_id','$nomor_antrean','$tanggal_antrean',
            '$total_biaya','Cash','Pending','Menunggu'
        )
    ");

    $id = mysqli_insert_id($conn);
    header("Location: ../user/konfirmasi_antrean.php?id=$id");
    exit;
}

/* ================= MIDTRANS ================= */
if ($metode_pembayaran === 'Midtrans') {

    mysqli_query($conn, "
        INSERT INTO tblantrean (
            nama_pelanggan, ServiceId, nomor_antrean, tanggal_antrean,
            total_biaya, metode_pembayaran, status_pembayaran,
            status_antrean, kode_transaksi_midtrans
        ) VALUES (
            '$nama_pelanggan','$service_id','$nomor_antrean','$tanggal_antrean',
            '$total_biaya','Midtrans','Pending','Menunggu','$order_id'
        )
    ");

    \Midtrans\Config::$serverKey    = 'Mid-server-*********************';
    \Midtrans\Config::$isProduction = false;
    \Midtrans\Config::$isSanitized  = true;
    \Midtrans\Config::$is3ds        = true;

    $params = [
        'transaction_details' => [
            'order_id' => $order_id,
            'gross_amount' => $total_biaya
        ],
        'item_details' => [[
            'id' => $service_id,
            'price' => $total_biaya,
            'quantity' => 1,
            'name' => $service['ServiceName']
        ]],
        'customer_details' => [
            'first_name' => $nama_pelanggan
        ],
        'callbacks' => [
            'finish' => 'http://localhost/Barber/user/payment_finish.php?order_id=' . $order_id
        ]
    ];

    $snap = \Midtrans\Snap::createTransaction($params);
    header("Location: " . $snap->redirect_url);
    exit;
}

header("Location: ../user/booking.php?error=Metode tidak dikenal");
exit;
