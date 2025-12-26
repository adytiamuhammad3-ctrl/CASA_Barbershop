<?php
include('inc/koneksi.php');

$id = $_GET['id'];

mysqli_query($conn, "
  UPDATE tblantrean 
  SET status_antrean='Diproses' 
  WHERE id_antrean='$id'
");

header("Location: antrean.php");
exit;
