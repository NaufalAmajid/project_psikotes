<?php
require_once 'classes/User.php';
$user = new User();
$allUser = $user->getAllUser();
?>
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">List User</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="javascript:void(0)" onclick="modalFormUser(0)" class="btn btn-primary float-end" type="button"><i class="ri-draft-fill"></i> Tambah User</a>
            </div>
            <div class="card-body">
                <table id="table-daftar-user" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama User</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $noUser = 1;
                        foreach ($allUser as $user) :
                        ?>
                            <tr>
                                <td><?= $noUser++ ?></td>
                                <td><?= $user['nama_lengkap'] ?></td>
                                <td><?= $user['username'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td>
                                    <?php if ($user['role_id'] == 1) : ?>
                                        <span class="badge bg-pink-subtle text-pink"><?= $user['nama_role'] ?></span>
                                    <?php else : ?>
                                        <span class="badge bg-purple-subtle text-purple"><?= $user['nama_role'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($_SESSION['user']['id_user'] != $user['id_user']) : ?>
                                        <a href="javascript:void(0)" onclick="modalFormUser(<?= $user['id_user'] ?>)" class="btn btn-info" type="button"><i class="ri-pencil-line"></i></a>
                                        <a href="javascript:void(0)" onclick="deleteUser(<?= $user['id_user'] ?>)" class="btn btn-danger" type="button"><i class="ri-delete-bin-line"></i></a>
                                    <?php else : ?>
                                        <a href="?page=profile" class="btn btn-purple" type="button"><i class="ri-profile-line"></i></a>
                                    <?php endif; ?>
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
        var tableBank = $('#table-daftar-user').DataTable({
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
        tableBank.buttons().container().appendTo("#table-daftar-user_wrapper .col-md-6:eq(0)");
    });

    function modalFormUser(id_user) {
        $.ajax({
            url: 'content/modal-form-user.php',
            type: 'post',
            data: {
                id_user: id_user
            },
            success: function(response) {
                $('#myModal').html(response);
                $('#myModal').modal('show');
            }
        });
    }

    function deleteUser(id_user) {
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
                    url: 'classes/User.php',
                    type: 'post',
                    data: {
                        id_user: id_user,
                        action: 'deleteUser'
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