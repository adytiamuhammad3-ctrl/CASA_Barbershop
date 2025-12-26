<?php
session_start();
error_reporting(0);
include('../inc/koneksi.php');

// Cek login admin (sesuaikan dengan sistem login Anda)
if (!isset($_SESSION['adminid'])) {
    header('location:../login.php');
    exit;
}

// Update status antrean
if (isset($_GET['updateid']) && isset($_GET['status'])) {
    $id = intval($_GET['updateid']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);

    mysqli_query($conn,
        "UPDATE tblantrean 
         SET status_antrean='$status' 
         WHERE id_antrean='$id'"
    );
    header("location:antrean.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Data Antrean</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<div class="container-fluid mt-4">
    <h3 class="fw-bold mb-4">📋 Data Antrean Pelanggan</h3>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nomor Antrean</th>
                        <th>Nama Pelanggan</th>
                        <th>Layanan</th>
                        <th>Total Biaya</th>
                        <th>Pembayaran</th>
                        <th>Status Antrean</th>
                        <th>Waktu Pesan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $query = mysqli_query($conn,"
                    SELECT 
                        a.id_antrean,
                        a.nomor_antrean,
                        a.nama_pelanggan,
                        s.ServiceName,
                        a.total_biaya,
                        a.metode_pembayaran,
                        a.status_antrean,
                        a.waktu_pesan
                    FROM tblantrean a
                    JOIN tblservices s ON s.ID = a.ServiceId
                    ORDER BY a.waktu_pesan DESC
                ");

                $no = 1;
                while ($row = mysqli_fetch_array($query)) {
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><strong><?= $row['nomor_antrean']; ?></strong></td>
                        <td><?= $row['nama_pelanggan']; ?></td>
                        <td><?= $row['ServiceName']; ?></td>
                        <td>Rp <?= number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                        <td><?= $row['metode_pembayaran']; ?></td>
                        <td>
                            <span class="badge 
                                <?php
                                if ($row['status_antrean'] == 'Menunggu') echo 'bg-warning';
                                elseif ($row['status_antrean'] == 'Diproses') echo 'bg-info';
                                else echo 'bg-success';
                                ?>">
                                <?= $row['status_antrean']; ?>
                            </span>
                        </td>
                        <td><?= date('d-m-Y H:i', strtotime($row['waktu_pesan'])); ?></td>
                        <td>
                            <a href="antrean.php?updateid=<?= $row['id_antrean']; ?>&status=Menunggu" 
                               class="btn btn-sm btn-warning">Menunggu</a>

                            <a href="antrean.php?updateid=<?= $row['id_antrean']; ?>&status=Diproses" 
                               class="btn btn-sm btn-info">Diproses</a>

                            <a href="antrean.php?updateid=<?= $row['id_antrean']; ?>&status=Selesai" 
                               class="btn btn-sm btn-success"
                               onclick="return confirm('Tandai antrean selesai?');">
                               Selesai
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
