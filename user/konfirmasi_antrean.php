<?php
session_start();
include('../inc/koneksi.php');

$antrean_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($antrean_id == 0) {
    die("Antrean tidak ditemukan");
}

$query = "
    SELECT 
        ta.*, 
        ts.ServiceName
    FROM tblantrean ta
    JOIN tblservices ts ON ta.ServiceId = ts.ID
    WHERE ta.id_antrean = $antrean_id
";
$data = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (!$data) {
    die("Data antrean tidak ditemukan");
}

/**
 * LOGIKA STATUS
 */
$is_paid = false;

// MIDTRANS + PAID
if ($data['metode_pembayaran'] === 'Midtrans' && $data['status_pembayaran'] === 'Paid') {
    $is_paid = true;
}

// CASH → LANGSUNG DIANGGAP SELESAI
if ($data['metode_pembayaran'] === 'Cash') {
    $is_paid = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Antrean</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nomor-besar { font-size: 5rem; font-weight: bold; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
<div class="row justify-content-center">
<div class="col-md-6">

<div class="card p-4 shadow text-center">

<?php if ($is_paid): ?>

    <!-- ================= BERHASIL ================= -->
    <h3 class="text-success fw-bold">Pembayaran Berhasil ✅</h3>
    <p class="text-muted">Silakan cetak atau simpan nomor antrean Anda</p>

    <div class="my-4">
        <h5>Nomor Antrean</h5>
        <div class="nomor-besar text-success">
            <?= htmlspecialchars($data['nomor_antrean']) ?>
        </div>
    </div>

    <table class="table table-borderless text-start">
        <tr><td>Nama</td><td>: <?= $data['nama_pelanggan'] ?></td></tr>
        <tr><td>Layanan</td><td>: <?= $data['ServiceName'] ?></td></tr>
        <tr><td>Total Biaya</td><td>: Rp <?= number_format($data['total_biaya'],0,',','.') ?></td></tr>
        <tr><td>Metode Bayar</td><td>: <?= $data['metode_pembayaran'] ?></td></tr>
        <tr><td>Status</td><td>: <span class="badge bg-success">Paid</span></td></tr>
    </table>

    <div class="d-grid gap-2 mt-4">
        <button onclick="window.print()" class="btn btn-outline-success">Cetak Antrean</button>
        <a href="../index.php" class="btn btn-success">Kembali ke Beranda</a>
    </div>

<?php else: ?>

    <!-- ================= MENUNGGU ================= -->
    <h3 class="text-warning fw-bold">Menunggu Pembayaran ⏳</h3>
    <p>Silakan selesaikan pembayaran QRIS.<br>Halaman ini akan otomatis diperbarui.</p>

    <div class="my-4">
        <h5>Nomor Antrean</h5>
        <div class="nomor-besar text-warning">
            <?= htmlspecialchars($data['nomor_antrean']) ?>
        </div>
    </div>

    <table class="table table-borderless text-start">
        <tr><td>Nama</td><td>: <?= $data['nama_pelanggan'] ?></td></tr>
        <tr><td>Layanan</td><td>: <?= $data['ServiceName'] ?></td></tr>
        <tr><td>Total Biaya</td><td>: Rp <?= number_format($data['total_biaya'],0,',','.') ?></td></tr>
        <tr><td>Metode Bayar</td><td>: <?= $data['metode_pembayaran'] ?></td></tr>
        <tr><td>Status</td><td>: <span class="badge bg-warning text-dark">Pending</span></td></tr>
    </table>

<?php endif; ?>

</div>
</div>
</div>
</div>

</body>
</html>
