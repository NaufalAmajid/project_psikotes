<?php
require_once '../config/connection.php';
require_once '../classes/DB.php';
require_once '../classes/User.php';

$user = new User();
$id_user = $_POST['id_user'];
$dataUser = $user->getUserById($id_user);
?>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myCenterModalLabel">Form User</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form-user">
                <input type="hidden" name="id_user" value="<?= $id_user ?>">
                <div class="mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= $dataUser ? $dataUser['nama_lengkap'] : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= $dataUser ? $dataUser['username'] : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value="123" <?= $dataUser ? 'disabled' : '' ?>>
                    <?php if ($dataUser) : ?>
                        <small class="text-danger">*password hanya bisa diubah oleh user</small>
                    <?php else : ?>
                        <small class="text-muted">*password default adalah 123</small>
                    <?php endif; ?>

                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $dataUser ? $dataUser['email'] : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="role_id" class="form-label">Role</label>
                    <select class="form-select" id="role_id" name="role_id">
                        <option value="">Pilih Role</option>
                        <?php
                        $allRole = $user->getAllRole();
                        foreach ($allRole as $role) :
                        ?>
                            <option value="<?= $role['id_role'] ?>" <?= $dataUser ? ($dataUser['role_id'] == $role['id_role'] ? 'selected' : '') : '' ?>><?= $role['nama_role'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="saveUser('<?= $id_user ?>', '<?= $id_user == 0 ? 'add' : 'edit' ?>')">Simpan</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    function saveUser(id_user, statusForm) {
        let form = $('#form-user').serializeArray();
        let send = {}
        let empty = []
        form.forEach(item => {
            if (item.value == '') {
                empty.push(item.name)
            } else {
                send[item.name] = item.value
            }
        })
        send['action'] = 'saveUser'
        send['statusForm'] = statusForm

        if (empty.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: empty.join(', ') + ' tidak boleh kosong'
            })
            return
        }

        $.ajax({
            url: 'classes/User.php',
            type: 'POST',
            data: send,
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
        })
    }
</script>