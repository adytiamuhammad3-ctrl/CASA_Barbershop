<?php
// ===============================
// MIDTRANS WEBHOOK (FINAL)
// ===============================

error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../inc/koneksi.php');
require_once('../Midtrans/Midtrans.php');

// KONFIGURASI MIDTRANS
\Midtrans\Config::$isProduction = false; // sandbox
\Midtrans\Config::$serverKey = 'Mid-server-*********************';

// AMBIL RAW JSON
$rawBody = file_get_contents("php://input");
$notification = json_decode($rawBody, true);

// VALIDASI JSON
if (!$notification) {
    http_response_code(400);
    echo "Invalid JSON";
    exit;
}

// VERIFIKASI SIGNATURE
$signatureKey = hash(
    'sha512',
    $notification['order_id'] .
    $notification['status_code'] .
    $notification['gross_amount'] .
    \Midtrans\Config::$serverKey
);

if ($signatureKey !== $notification['signature_key']) {
    http_response_code(401);
    echo "Invalid signature";
    exit;
}

// DATA TRANSAKSI
$order_id = $notification['order_id'];
$status = $notification['transaction_status'];
$fraud = $notification['fraud_status'] ?? null;

// MAPPING STATUS
$new_status = 'Pending';

if ($status === 'settlement') {
    $new_status = 'Paid';
} elseif ($status === 'capture' && $fraud === 'accept') {
    $new_status = 'Paid';
} elseif ($status === 'deny' || $status === 'expire' || $status === 'cancel') {
    $new_status = 'Failed';
}

// UPDATE DATABASE
$stmt = mysqli_prepare(
    $conn,
    "UPDATE tblantrean 
     SET status_pembayaran = ? 
     WHERE kode_transaksi_midtrans = ?"
);
mysqli_stmt_bind_param($stmt, "ss", $new_status, $order_id);
mysqli_stmt_execute($stmt);

http_response_code(200);
echo "OK";
exit;