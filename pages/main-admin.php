<?php
require_once 'classes/Main.php';
$main = new Main();
$data = $main->getAllData();
?>
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Welcome <?= ucwords($_SESSION['user']['nama_role']) ?>, <?= ucwords($_SESSION['user']['nama_lengkap']) ?> !</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat text-bg-pink">
                <div class="card-body">
                    <div class="float-end">
                        <i class="ri-file-list-3-line widget-icon"></i>
                    </div>
                    <h6 class="text-uppercase mt-0" title="Customers">Data Laporan</h6>
                    <h2 class="my-2"><?= $data['total_laporan'] ?></h2>
                </div>
            </div>
        </div> <!-- end col-->

        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat text-bg-purple">
                <div class="card-body">
                    <div class="float-end">
                        <i class="ri-pages-line widget-icon"></i>
                    </div>
                    <h6 class="text-uppercase mt-0" title="Customers">Soal</h6>
                    <h2 class="my-2"><?= $data['total_soal'] ?></h2>
                </div>
            </div>
        </div> <!-- end col-->

        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat text-bg-info">
                <div class="card-body">
                    <div class="float-end">
                        <i class="ri-user-2-fill widget-icon"></i>
                    </div>
                    <h6 class="text-uppercase mt-0" title="Customers">Admin</h6>
                    <h2 class="my-2"><?= $data['total_admin'] ?></h2>
                </div>
            </div>
        </div> <!-- end col-->

        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat text-bg-primary">
                <div class="card-body">
                    <div class="float-end">
                        <i class="ri-group-2-line widget-icon"></i>
                    </div>
                    <h6 class="text-uppercase mt-0" title="Customers">Peserta</h6>
                    <h2 class="my-2"><?= $data['total_user'] ?></h2>
                </div>
            </div>
        </div> <!-- end col-->
    </div>

</div>