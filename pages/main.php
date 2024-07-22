<?php
require_once 'classes/Setting.php';
require_once 'classes/Soal.php';
require_once 'classes/Pengerjaan.php';
$pengerjaan = new Pengerjaan();
$checkPengerjaan = $pengerjaan->getPengerjaanByIdUser($_SESSION['user']['id_user']);
$soalClass = new Soal();
$soal = $soalClass->getAllSoal();
$setting = new Setting();
$setting = $setting->getSetting();
?>
<?php if ($_SESSION['user']['id_role'] == 1) : ?>
    <?php include 'main-admin.php'; ?>
<?php else : ?>
    <?php if (isset($_GET['pengerjaan'])) : ?>
        <?php include 'content/pengerjaan-soal.php'; ?>
    <?php else : ?>
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
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
                                    <?php if ($checkPengerjaan && $checkPengerjaan['status_pengerjaan'] == 0) : ?>
                                        Anda sedang mengerjakan soal <b>Psikotes</b>. Silahkan lanjutkan pengerjaan soal <b>Psikotes</b> yang telah dimulai.
                                        Waktu pengerjaan terus berjalan.
                                    <?php elseif ($checkPengerjaan && $checkPengerjaan['status_pengerjaan'] == 1) : ?>
                                        Anda telah menyelesaikan pengerjaan soal <b>Psikotes</b>.
                                    <?php else : ?>
                                        Anda akan mendapatkan soal <b>Psikotes</b> yang harus dijawab dalam waktu tertentu.
                                        <br>
                                        <br>
                                        <strong>Waktu Pengerjaan : <?= $setting['waktu_pengerjaan'] ?> Menit</strong>
                                        <br>
                                        <strong>Jumlah Soal : <?= count($soal) ?> Soal</strong>
                                    <?php endif; ?>
                                </p>
                                <?php if ($checkPengerjaan && $checkPengerjaan['status_pengerjaan'] == 0) : ?>
                                    <a href="dashboard.php?mydashboard&pengerjaan" class="btn btn-success">Lanjutkan Pengerjaan</a>
                                <?php elseif ($checkPengerjaan && $checkPengerjaan['status_pengerjaan'] == 1) : ?>
                                    <span class="badge badge-success">Pengerjaan Selesai</span>
                                <?php else : ?>
                                    <a href="javascript: void(0);" class="btn btn-success" onclick="<?= count($emptyProfile) > 0 ? 'unComplatedProfile()' : 'readyToExecution()' ?>">Kerjakan</a>
                                <?php endif; ?>
                            </div> <!-- end card-body-->
                        </div> <!-- end col-->
                    </div>
                    <!-- end row -->
                </div>
            </div>
        </div>
    <?php endif; ?>
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

        function readyToExecution() {
            Swal.fire({
                title: 'Informasi!',
                text: 'Pilih "Kerjakan" untuk memulai pengerjaan soal Psikotes.',
                icon: 'warning',
                confirmButtonText: 'Kerjakan',
                showCancelButton: true,
                cancelButtonText: 'Tutup',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'dashboard.php?mydashboard&pengerjaan';
                }
            });
        }
    </script>
<?php endif; ?>