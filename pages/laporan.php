<?php
require_once 'classes/Laporan.php';
$laporan = new Laporan();
$laporanAll = $laporan->getLaporan();
?>
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Laporan Pengerjaan Psikotes</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="table-laporan" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>NIK</th>
                            <th>Tempat, Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($laporanAll as $lap) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $lap['nama_lengkap'] ?></td>
                                <td><?= $lap['email'] ?></td>
                                <td><?= $lap['nik'] ?></td>
                                <td><?= $lap['tempat_lahir'] . ', ' . $lap['tanggal_lahir'] ?></td>
                                <td><?= $lap['jenis_kelamin'] ?></td>
                                <td>
                                    <?php
                                    $result = json_decode($lap['hasil'], true);
                                    ?>
                                    Jumlah Soal : <?= $result['jumlah_soal'] ?><br>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill">Betul</span> : <?= $result['pass_answered'] ?><br>
                                    <span class="badge bg-danger-subtle text-danger rounded-pill">Salah</span> : <?= $result['wrong_answered'] ?><br>
                                    <span class="badge bg-warning-subtle text-warning rounded-pill">Tidak Dijawab</span> : <?= $result['not_answered'] ?><br>
                                    Total Skor : <?= $result['total_skor'] ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var tableBank = $('#table-laporan').DataTable({
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
        tableBank.buttons().container().appendTo("#table-laporan_wrapper .col-md-6:eq(0)");
    });
</script>