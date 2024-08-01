<?php
require_once 'classes/Setting.php';
$setting = new Setting();
$settingData = $setting->getSetting();
?>
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Pengaturan App Psikotes</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
</div>
<div class="row">
    <div class="col-3"></div>
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <form id="form-setting">
                    <div class="row g-2">
                        <div class="mb-3 col-md-4">
                            <label for="waktu_pengerjaan" class="form-label">Waktu Pengerjaan</label>
                            <input type="number" class="form-control" id="waktu_pengerjaan" name="waktu_pengerjaan" placeholder="waktu pengerjaan ..." value="<?= $settingData['waktu_pengerjaan'] ?>">
                            <small class="form-text text-muted">Waktu pengerjaan dalam menit</small>
                        </div>
                        <div class="mb-3 col-md-8">
                            <label for="penilaian" class="form-label">Penilaian</label>
                            <input type="text" class="form-control" id="penilaian" name="penilaian" placeholder="penilaian ..." value="<?= $settingData['penilaian'] ?>">
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="saveSetting()">Simpan Perubahan</button>
                </form>

            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div>
    <div class="col-3"></div>
</div>
<!-- end row -->
<script>
    function saveSetting() {
        let form = $('#form-setting').serializeArray();
        let send = {};
        form.forEach(item => {
            send[item.name] = item.value;
        });
        send['action'] = 'updateSetting';
        $.ajax({
            url: 'classes/Setting.php',
            type: 'POST',
            data: send,
            success: function(response) {
                let data = JSON.parse(response);
                Swal.fire({
                    icon: data.status,
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            }
        });
    }
</script>