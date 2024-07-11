<?php
require_once 'classes/Soal.php';
$soalClass = new Soal();
$no_soal = date('YmdHis');
?>
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="?page=soal">List Bank Soal</a></li>
                        <?php if (isset($_GET['menu'])) : ?>
                            <li class="breadcrumb-item"><a href="?page=soal&menu=baru&nosoal=<?= $_GET['nosoal'] ?>">Soal Baru</a></li>
                        <?php endif; ?>
                    </ol>
                </div>
                <h4 class="page-title">Bank Soal</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <?php if (isset($_GET['menu'])) : ?>
        <?php include 'content/soal/' . $_GET['menu'] . '.php'; ?>
    <?php else : ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="?page=soal&menu=baru&nosoal=<?= $no_soal ?>" class="btn btn-primary float-end" type="button"><i class="ri-draft-fill"></i> Buat Soal</a>
                    </div>
                    <div class="card-body">
                        <table id="table-bank-soal" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Bank Soal</th>
                                    <th>No Soal</th>
                                    <th>Jumlah Soal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $allBank = $soalClass->getAllBankSoal();
                                $noBankSoal = 1;
                                ?>
                                <?php foreach ($allBank as $bank) : ?>
                                    <tr>
                                        <td><?= $noBankSoal++ ?></td>
                                        <td><?= $bank['nama_soal'] ?></td>
                                        <td><?= $bank['no_soal'] ?></td>
                                        <td><?= $bank['jumlah_soal'] ?></td>
                                        <td>
                                            <a href="?page=soal&menu=baru&nosoal=<?= $bank['no_soal'] ?>" class="btn btn-primary" type="button"><i class="ri-folder-shared-line"></i></a>
                                            <a href="javascript:void(0)" class="btn btn-danger" type="button"><i class="ri-folder-forbid-fill"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<script>
    $(document).ready(function() {
        var tableBank = $('#table-bank-soal').DataTable({
            lengthChange: !1,
            buttons: ["copy", "print"],
            language: {
                paginate: {
                    previous: "<i class='ri-arrow-left-s-line'>",
                    next: "<i class='ri-arrow-right-s-line'>",
                },
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            },
        });
        tableBank.buttons().container().appendTo("#table-bank-soal_wrapper .col-md-6:eq(0)");
    });
</script>