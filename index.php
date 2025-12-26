<?php
session_start();
error_reporting(0);
include('inc/koneksi.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css" />
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
  <link rel="shorcut icon" type="image/png" href="assets/img/logo.png" />
  <script src="assets/sciript.js" defer></script>

  <title>CASA Barbershop</title>
</head>

<body>

<?php include 'inc/header.php'; ?>

<button class="go-top-btn">
  <img src="assets/img/arrow-up.png" alt="arrow up">
</button>

<!-- HERO SECTION -->
<section class="hero container">
  <div class="mt-5">
    <div class="row ms-3">
      <div class="col-md-6">
        <h1 class="fw-bold">CASA Barbershop</h1>
        <p class="text-muted mt-3">
          Temukan tukang cukur profesional yang membuat rambut Anda terlihat
          <div id="typing">Tampan dan Rapi</div>
          <div id="line">|</div>
        </p>
        <a href="user/booking.php" class="btn btn-color-theme pe-4 ps-4 pt-2 mt-3">
          Pesan Sekarang
        </a>
        <a href="user/login.php" class="btn btn-outline-theme pe-4 ps-4 pt-2 mt-3">
          Get Invoices
        </a>
      </div>
      <div class="col-md-6 mt-2">
        <img src="assets/img/banner.svg" class="img-banner img-fluid" width="500px">
      </div>
    </div>
  </div>
</section>

<!-- LAYANAN -->
<span id="service"></span>
<section class="popular-barber bg-theme pt-2 pb-2 mt-5">
  <div class="container">
    <div class="row">
      <h3 class="fw-bold ms-3">
        Daftar <span class="text-theme">Layanan</span>
      </h3>
      <hr>
    </div>

    <div class="row mt-3">
      <?php
      $ret = mysqli_query($conn, "SELECT * FROM tblservices");
      while ($row = mysqli_fetch_array($ret)) {
      ?>
      <div class="col-md-4 mt-3">
        <div class="card border-radius-10 p2">
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <img src="./admin/assets/img/<?php echo $row['Image']; ?>" width="100" class="border-radius-10 img-fluid">
              </div>
              <div class="col-md-8 mt-2">
                <h5 class="ms-2 fw-bold"><?php echo $row['ServiceName']; ?></h5>
                <small class="ms-2 fw-bold text-theme">Pelanggan</small><br>
                <small class="text-muted ms-2">Katapang, Bandung</small>
              </div>
            </div>

            <h5 class="fw-bold mt-4">Service Description</h5>
            <p class="text-muted"><?php echo $row['ServiceDescription']; ?></p>

            <h5 class="price-theme">Biaya layanan: Rp.<?php echo $row['Cost']; ?>k</h5>

            <a href="user/booking.php?serviceid=<?php echo $row['ID']; ?>" class="btn btn-sm btn-outline-theme mt-2">
              Pesan Layanan Ini
            </a>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</section>

<!-- LOKASI -->
<span id="location"></span>
<section class="container mt-5">
  <?php
  $ret = mysqli_query($conn, "SELECT * FROM tblpage WHERE PageType='location'");
  while ($row = mysqli_fetch_array($ret)) {
  ?>
  <div class="row">
    <div class="col-md-6">
      <?php echo $row['PageDescription']; ?>
    </div>
    <div class="col-md-6">
      <h2 class="fw-bold"><?php echo $row['PageTitle']; ?></h2>
      <hr>
      <p class="text-muted">
        CASA Barbershop belum membuka cabang lain dan hanya tersedia di lokasi tersebut.
      </p>
    </div>
  </div>
  <?php } ?>
</section>

<?php include 'inc/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script>feather.replace();</script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>AOS.init();</script>

</body>
</html>