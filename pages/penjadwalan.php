<?php
require_once 'classes/Penjadwalan.php';
$penjadwalan = new Penjadwalan();
?>
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">List Penjadwalan</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="javascript:void(0)" onclick="modalFormPenjadwalan(0)" class="btn btn-primary float-end" type="button"><i class="ri-draft-fill"></i> Tambah Jadwal</a>
            </div>
            <div class="card-body">
                <table id="table-daftar-jadwal" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Peserta</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($penjadwalan->getPenjadwalan() as $no_jadwal => $jadwal) :
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $jadwal['hari'] . ' ' . $jadwal['jam'] ?></td>
                                <td>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mt-lg-0 mt-3">
                                                <div class="avatar-group">
                                                    <?php foreach ($jadwal['peserta'] as $peserta) : ?>
                                                        <?php
                                                        // $img = file_exists('myfiles/photo/' . $peserta['photo_profile']) ? 'myfiles/photo/' . $peserta['photo_profile'] : 'assets/images/profile.png';
                                                        if (file_exists('myfiles/photo/' . $peserta['photo_profile']) && $peserta['photo_profile'] != '') {
                                                            $img = 'myfiles/photo/' . $peserta['photo_profile'];
                                                        } else {
                                                            $img = 'assets/images/profile.png';
                                                        }
                                                        ?>
                                                        <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $peserta['nama_lengkap'] ?>">
                                                            <img src="<?= $img ?>" alt="<?= $img ?>" class="rounded-circle avatar-sm">
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" onclick="modalFormPenjadwalan('<?= $no_jadwal ?>')" class="btn btn-info btn-sm" type="button"><i class="ri-pencil-fill"></i></a>
                                    <a href="javascript:void(0)" onclick="deleteJadwal('<?= $no_jadwal ?>')" class="btn btn-danger btn-sm" type="button"><i class="ri-delete-bin-fill"></i></a>
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
        var tableBank = $('#table-daftar-jadwal').DataTable({
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
        tableBank.buttons().container().appendTo("#table-daftar-jadwal_wrapper .col-md-6:eq(0)");
    });

    function modalFormPenjadwalan(no_jadwal) {
        $.ajax({
            url: 'content/modal-form-penjadwalan.php',
            type: 'post',
            data: {
                no_jadwal: no_jadwal
            },
            success: function(response) {
                $('#myModal').html(response);
                $('#myModal').modal('show');
            }
        });
    }

    function deleteJadwal(no_jadwal) {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'classes/Penjadwalan.php',
                    type: 'post',
                    data: {
                        no_jadwal: no_jadwal,
                        action: 'deleteJadwal'
                    },
                    success: function(response) {
                        let res = JSON.parse(response)
                        Swal.fire({
                            icon: res.status,
                            title: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            if (res.status == 'success') {
                                window.location.reload()
                            }
                        })
                    }
                });
            }
        })
    }
</script>