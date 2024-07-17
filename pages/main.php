<?php
require 'classes/Setting.php';
require 'classes/Soal.php';
$soal = new Soal();
$soal = $soal->getAllSoal();
$setting = new Setting();
$setting = $setting->getSetting();
?>
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Velonic</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                        <li class="breadcrumb-item active">Welcome!</li>
                    </ol>
                </div>
                <h4 class="page-title">Welcome <?= ucwords($_SESSION['user']['nama_role']) ?>, <?= ucwords($_SESSION['user']['nama_lengkap']) ?> !</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <?php
            $emptyProfile = [];
            foreach ($_SESSION['user'] as $key => $value) {
                if ($value == null || $value == '') {
                    $emptyProfile[] = ucwords(str_replace('_', ' ', $key));
                }
            }
            ?>
            <?php if (count($emptyProfile) > 0) : ?>
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Peringatan!</h4>
                    <p>Biodata anda belum lengkap, silahkan lengkapi profile anda terlebih dahulu.</p>
                    <hr>
                    <p class="mb-0">Profile yang belum lengkap : <br><?= implode('<br> ', $emptyProfile) ?></p>
                </div>
            <?php endif; ?>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-body border-success border">
                        <h5 class="card-title text-success">Soal Psikotes</h5>
                        <p class="card-text">
                            Anda akan mendapatkan soal <b>Psikotes</b> yang harus dijawab dalam waktu tertentu.
                            <br>
                            <br>
                            <strong>Waktu Pengerjaan : <?= $setting['waktu_pengerjaan'] ?> Menit</strong>
                            <br>
                            <strong>Jumlah Soal : <?= count($soal) ?> Soal</strong>
                        </p>
                        <a href="javascript: void(0);" class="btn btn-success" onclick="<?= count($emptyProfile) > 0 ? 'unComplatedProfile()' : 'readyToExecution()' ?>">Kerjakan</a>
                    </div> <!-- end card-body-->
                </div> <!-- end col-->
            </div>
            <!-- end row -->
            <?php
            echo '<pre>';
            print_r($_SESSION);
            echo '</pre>';
            ?>
        </div>
    </div>
</div>
<script>
    function unComplatedProfile() {
        Swal.fire({
            title: 'Peringatan!',
            text: 'Biodata anda belum lengkap, silahkan lengkapi profile anda terlebih dahulu.',
            icon: 'warning',
            confirmButtonText: 'Lengkapi Profile',
            showCancelButton: true,
            cancelButtonText: 'Tutup',
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'dashboard.php?page=profile';
            }
        });
    }
</script>