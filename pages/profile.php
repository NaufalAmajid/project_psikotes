<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="profile-bg-picture" style="background-image:url('assets/images/bg-profile.png')">
                <span class="picture-bg-overlay"></span>
                <!-- overlay -->
            </div>
            <!-- meta -->
            <div class="profile-user-box">
                <div class="row">
                    <div class="col-sm-6">
                        <?php
                        $photoProfile = $_SESSION['user']['photo_profile'] ? 'myfiles/photo/' . $_SESSION['user']['photo_profile'] : 'assets/images/avatar-placehoder.jpg';
                        ?>
                        <div class="profile-user-img">
                            <img src="<?= $photoProfile ?>" onclick="triggerClick(this)" id="placeholder-image" alt="photo_profile_<?= $_SESSION['user']['username'] ?>" class="avatar-lg rounded-circle">
                            <input type="file" class="form-control d-none" onchange="displayImage(this)" id="photo_profile" name="photo_profile">
                        </div>
                        <div class="">
                            <h4 class="mt-4 fs-17 ellipsis"><?= ucwords($_SESSION['user']['nama_lengkap']) ?></h4>
                            <p class="font-13"><?= $_SESSION['user']['email'] ?></p>
                            <p class="text-muted mb-0"><small><?= ucwords($_SESSION['user']['nama_role']) ?></small></p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex justify-content-end align-items-center gap-2">
                            <button type="button" class="btn btn-soft-danger">
                                <i class="ri-user-unfollow-line align-text-bottom me-1 fs-16 lh-1"></i>
                                Hapus Akun
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ meta -->
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-sm-12">
            <div class="card p-0">
                <div class="card-body p-3">
                    <form id="form-edit-profile">
                        <div class="row row-cols-sm-2 row-cols-1">
                            <div class="mb-2">
                                <label class="form-label" for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" value="<?= ucwords($_SESSION['user']['nama_lengkap']) ?>" id="nama_lengkap" name="nama_lengkap" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" value="<?= $_SESSION['user']['email'] ?>" id="email" name="email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="username">Username</label>
                                <input type="text" value="<?= $_SESSION['user']['username'] ?>" id="username" name="username" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" placeholder="..." id="password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="change_password">Ganti Password</label>
                                <input type="password" placeholder="..." id="change_password" readonly class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="new_password">Konfirm Password Baru</label>
                                <input type="password" placeholder="..." id="new_password" name="password" readonly class="form-control">
                            </div>
                        </div>
                        <h3 class="mt-4">Tentang</h3>
                        <hr>
                        <div class="row row-cols-sm-2 row-cols-1">
                            <div class="mb-3">
                                <label class="form-label" for="nik">NIK</label>
                                <input type="text" value="<?= $_SESSION['user']['nik'] ?>" id="nik" name="nik" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="no_hp">No HP</label>
                                <input type="text" value="<?= $_SESSION['user']['no_hp'] ?>" id="no_hp" name="no_hp" class="form-control">
                            </div>
                        </div>
                        <div class="row row-cols-sm-3 row-cols-1">
                            <div class="mb-3">
                                <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" <?= $_SESSION['user']['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="Perempuan" <?= $_SESSION['user']['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                                <input type="text" value="<?= $_SESSION['user']['tempat_lahir'] ?>" id="tempat_lahir" name="tempat_lahir" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" value="<?= $_SESSION['user']['tanggal_lahir'] ?>" id="tanggal_lahir" name="tanggal_lahir" class="form-control">
                            </div>
                        </div>
                        <button class="btn btn-primary" type="button" onclick="changeAccountUser('<?= $_SESSION['user']['id_user'] ?>')"><i class="ri-save-line me-1 fs-16 lh-1"></i> Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
</div>
<!-- end row -->
<script>
    function triggerClick(e) {
        document.querySelector('#photo_profile').click();
    }

    function displayImage(e) {
        if (e.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('#placeholder-image').setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(e.files[0]);
        }
    }

    function changeAccountUser(id_user) {
        let form = $('#form-edit-profile').serializeArray();
        let photo = $('#photo_profile').prop('files')[0];
        let newForm = new FormData();
        // check size photo if > 1mb then return false
        if (photo != undefined) {
            if (photo.size > 1000000) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ukuran foto terlalu besar, maksimal 1MB',
                });
                return false;
            }
            newForm.append('photo', photo);
        }
        newForm.append('action', 'updateAccountUser');
        newForm.append('id_user', id_user);
        form.forEach((item) => {
            newForm.append(item.name, item.value);
        });

        $.ajax({
            url: 'classes/User.php',
            type: 'POST',
            data: newForm,
            contentType: false,
            processData: false,
            success: function(response) {
                let data = JSON.parse(response);
                Swal.fire({
                    icon: data.status,
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    if (data.status == 'success') {
                        location.reload();
                    }
                });
            }
        });
    }
</script>