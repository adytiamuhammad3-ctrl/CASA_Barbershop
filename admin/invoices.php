<?php 
include 'inc/koneksi.php';
session_start();
error_reporting(0);

if($_SESSION['status']!="login"){
    header("Location: ../login.php?info=Login Terlebih Dahulu!");
    exit;
}

/* DELETE INVOICE */
if(isset($_GET['delid'])){
    $id = $_GET['delid'];
    mysqli_query($conn,"DELETE FROM tblantrean WHERE id_antrean='$id'");
    echo "<script>alert('Data berhasil dihapus');</script>";
    echo "<script>window.location='invoices.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Faktur - Admin Area</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

<!-- HEADER -->
<?php include 'inc/header.php'; ?>

<!-- SIDEBAR -->
<?php include 'inc/sidebar.php'; ?>

<main id="main" class="main">

  <!-- PAGE TITLE -->
  <div class="pagetitle">
    <h1>Faktur</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Faktur</li>
      </ol>
    </nav>
  </div>

  <!-- CONTENT -->
  <section class="section">
    <div class="card">
      <div class="card-body">

        <h5 class="card-title">Daftar Faktur</h5>

        <div class="table-responsive">
          <table class="table table-striped table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>ID Faktur</th>
                <th>Nama Pelanggan</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>

            <?php
            $no = 1;
            $query = mysqli_query($conn,"
              SELECT 
                a.id_antrean,
                a.nomor_antrean,
                a.nama_pelanggan,
                s.ServiceName,
                a.tanggal_antrean,
                a.status_pembayaran
              FROM tblantrean a
              JOIN tblservices s ON a.ServiceId = s.ID
              ORDER BY a.id_antrean DESC
            ");

            while($row = mysqli_fetch_assoc($query)){
            ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['nomor_antrean']; ?></td>
                <td><?= $row['nama_pelanggan']; ?></td>
                <td><?= $row['ServiceName']; ?></td>
                <td><?= date('d M Y', strtotime($row['tanggal_antrean'])); ?></td>
                <td>
                  <?php if($row['status_pembayaran']=='Paid'){ ?>
                    <span class="badge bg-success">Paid</span>
                  <?php } else { ?>
                    <span class="badge bg-warning text-dark">Pending</span>
                  <?php } ?>
                </td>
                <td class="text-center">
                  <a href="view-invoice.php?id=<?= $row['id_antrean']; ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="invoices.php?delid=<?= $row['id_antrean']; ?>" 
                     class="btn btn-sm btn-danger"
                     onclick="return confirm('Yakin ingin menghapus faktur ini?')">
                     <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            <?php } ?>

            </tbody>
          </table>
        </div>

      </div>
    </div>
  </section>

</main>

<?php include 'inc/footer.php'; ?>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>