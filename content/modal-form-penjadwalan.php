<?php
require_once '../config/connection.php';
require_once '../classes/DB.php';
require_once '../classes/User.php';
require_once '../classes/Penjadwalan.php';

$users = new User();
$users = $users->getAllUser('AND role_id = 2');

$penjadwalan = new Penjadwalan();
$penjadwalan = $penjadwalan->getJadwalByNoJadwal($_POST['no_jadwal']);

$no_jadwal = $_POST['no_jadwal'];
?>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myCenterModalLabel">Form Penjadwalan</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form-penjadwalan">
                <input type="hidden" name="no_jadwal" value="<?= $no_jadwal ?>">
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" value="<?= $penjadwalan ? $penjadwalan['tanggal'] : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="list-user" class="form-label">Peserta</label>
                    <select class="select2 form-control select2-multiple" id="list-user" multiple="multiple" data-placeholder="Choose ..." name="peserta">
                        <?php foreach ($users as $user) : ?>
                            <option value="<?= $user['id_user'] ?>" <?= $penjadwalan ? (in_array($user['id_user'], $penjadwalan['id_peserta']) ? 'selected' : '') : '' ?>><?= $user['nama_lengkap'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="savePenjadwalan('<?= $no_jadwal ?>', '<?= $no_jadwal == 0 ? 'add' : 'edit' ?>')">Simpan</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $(document).ready(function() {
        $('#list-user').select2({
            dropdownParent: $('#myModal')
        });
    })

    function savePenjadwalan(no_jadwal, statusForm) {
        let form = $('#form-penjadwalan').serializeArray();
        let peserta = $('#list-user').find(':selected').map(function() {
            return this.value;
        }).get();
        let send = {}
        let empty = []
        form.forEach(item => {
            if (item.value == '') {
                empty.push(item.name)
            } else {
                send[item.name] = item.value
            }
        })

        if (statusForm == 'add') {
            send['action'] = 'savePenjadwalan'
        } else {
            send['action'] = 'editPenjadwalan'
        }

        send['statusForm'] = statusForm
        send['peserta'] = peserta

        if (empty.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: empty.join(', ') + ' tidak boleh kosong'
            })
            return
        }

        $.ajax({
            url: 'classes/Penjadwalan.php',
            type: 'POST',
            data: send,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    html: response,
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload()
                    }
                })
            }
        })
    }
</script>