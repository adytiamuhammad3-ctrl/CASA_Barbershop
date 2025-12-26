<?php
session_start();
error_reporting(0);

include('../inc/koneksi.php'); 
include('../inc/header.php'); 

$selected_service_id = isset($_GET['serviceid']) ? (int)$_GET['serviceid'] : 0;
$services_query = mysqli_query($conn, "SELECT ID, ServiceName, Cost FROM tblservices");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pesan Antrean Barbershop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css" />
</head>
<body>

<section class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <h2 class="fw-bold mb-4 text-center">Formulir Pesan Antrean</h2>

            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php } ?>

            <div class="card p-4 shadow">
                <form action="../proses/proses_booking.php" method="POST" id="formBooking">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_pelanggan" required autocomplete="off">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Layanan</label>
                        <select class="form-select" id="service_id" name="service_id" required>
                            <option value="" disabled selected>-- Pilih Layanan --</option>
                            <?php 
                            while ($service = mysqli_fetch_assoc($services_query)) {
                                $selected = ($service['ID'] == $selected_service_id) ? 'selected' : '';
                                $display_cost = number_format($service['Cost'] * 1000, 0, ',', '.');
                                echo "<option value='{$service['ID']}' data-cost='{$service['Cost']}' $selected>";
                                echo "{$service['ServiceName']} (Rp {$display_cost})";
                                echo "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Metode Pembayaran</label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" value="Cash" required>
                            <label class="form-check-label">Tunai (Bayar di tempat)</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" value="Midtrans" required>
                            <label class="form-check-label">QRIS / Pembayaran Online</label>
                        </div>
                    </div>

                    <div class="alert alert-info d-none" id="biaya_info">
                        Total Biaya: <strong id="display_cost"></strong>
                    </div>

                    <div class="alert alert-warning d-none" id="qris_info">
                        Anda akan diarahkan ke halaman pembayaran QRIS Midtrans.
                    </div>

                    <button type="submit" class="btn btn-color-theme w-100 mt-3" id="btnSubmit">
                        Konfirmasi Pemesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include('../inc/footer.php'); ?>

<script>
const serviceSelect = document.getElementById('service_id');
const biayaInfo = document.getElementById('biaya_info');
const displayCost = document.getElementById('display_cost');
const qrisInfo = document.getElementById('qris_info');
const form = document.getElementById('formBooking');
const btn = document.getElementById('btnSubmit');

serviceSelect.addEventListener('change', function() {
    const cost = this.options[this.selectedIndex].getAttribute('data-cost');
    if (cost) {
        const total = parseInt(cost) * 1000;
        displayCost.textContent = 'Rp ' + total.toLocaleString('id-ID');
        biayaInfo.classList.remove('d-none');
    }
});

document.querySelectorAll('input[name="metode_pembayaran"]').forEach(el => {
    el.addEventListener('change', function() {
        if (this.value === 'Midtrans') {
            qrisInfo.classList.remove('d-none');
        } else {
            qrisInfo.classList.add('d-none');
        }
    });
});

form.addEventListener('submit', function() {
    btn.disabled = true;
    btn.textContent = 'Memproses...';
});
</script>

</body>
</html>