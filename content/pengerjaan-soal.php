<?php
$noSoal = 1;
$soalAndJawaban = $pengerjaan->soalAndJawabanByUserId($_SESSION['user']['id_user']);
$firstSoalId = $soalAndJawaban[0]['id_soal'];
$lastSoalId = $soalAndJawaban[count($soalAndJawaban) - 1]['id_soal'];
?>
<style>
    .radio-label {
        display: block;
        position: relative;
        padding-left: 30px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 16px;
        user-select: none;
    }

    .radio-label input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .radio-label .alphabet {
        position: absolute;
        left: 0;
        top: 0;
        width: 25px;
        height: 25px;
        background-color: #eee;
        /* border-radius: 50%; */
        border: 1px solid #2196F3;
        text-align: center;
        line-height: 25px;
        font-size: 14px;
        color: black;
    }

    .radio-label input:checked~.alphabet {
        background-color: #2196F3;
        color: white;
    }
</style>
<input type="hidden" id="id_user" value="<?= $_SESSION['user']['id_user'] ?>">
<input type="hidden" id="skor" value="<?= $setting['skor_soal'] ?>">
<input type="hidden" id="waktu" value="<?= $checkPengerjaan ? $checkPengerjaan['waktu'] : $setting['waktu_pengerjaan'] ?>">
<input type="hidden" id="start_time" value="<?= $checkPengerjaan ? strtotime($checkPengerjaan['start_time']) : 0 ?>">
<input type="hidden" id="checkPengerjaan" value="<?= $checkPengerjaan ? $checkPengerjaan['status_pengerjaan'] : 99 ?>">
<input type="hidden" id="firstSoalId" value="<?= $firstSoalId ?>">
<input type="hidden" id="lastSoalId" value="<?= $lastSoalId ?>">
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <h4 id="countdown-timer" class="text-danger"><span class="spinner-grow spinner-grow-sm m-2"></span></h4>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">Selamat Mengerjakan Tes, <?= ucwords($_SESSION['user']['nama_lengkap']) ?> !</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<?php if ($checkPengerjaan && $checkPengerjaan['status_pengerjaan'] == 0) : ?>
    <form id="form-pengerjaan-soal">
        <?php foreach ($soalAndJawaban as $key => $value) : ?>
            <div class="row d-none" id="execution-<?= $value['id_soal'] ?>">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= $noSoal++ ?>. &emsp;<?= nl2br($value['soal']) ?></h5>
                            <?php if ($value['kategori_id'] == 1) : ?>
                                <div class="row mb-2">
                                    <?php
                                    $fileSoal = json_decode($value['file'], true);
                                    ?>
                                    <div class="col">
                                        <?php foreach ($fileSoal as $file) : ?>
                                            <img src="myfiles/soal/<?= $value['id_soal'] ?>/<?= $file ?>" class="img-thumbnail mb-1 ms-1" width="100" height="1000">
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row mb-2">
                                <div class="col">
                                    <?php foreach ($soalClass->getPilgan() as $pilgan) : ?>
                                        <?php
                                        $alfa = explode('_', $pilgan['COLUMN_NAME']);
                                        $alfa = end($alfa);
                                        ?>
                                        <div class="form-check mb-3">
                                            <label class="radio-label">
                                                <?php
                                                $nextIdSoal = $key < count($soalAndJawaban) - 1 ? $soalAndJawaban[$key + 1]['id_soal'] : $lastSoalId;
                                                ?>
                                                <input type="radio" name="soal_<?= $value['id_soal'] ?>" id="soal_<?= $value['id_soal'] ?>_<?= $alfa ?>" value="<?= $alfa ?>" onclick="answerThis(this, '<?= $value['id_soal'] ?>', '<?= $nextIdSoal ?>')" <?= $alfa == $value['jawaban'] ? 'checked' : '' ?>>
                                                <span class='alphabet'><?= strtoupper($alfa) ?></span> <?= $value['kategori_id'] != 1 ? $value[$pilgan['COLUMN_NAME']] : '' ?>
                                                <?php if ($value['kategori_id'] == 1) : ?>
                                                    <img src="myfiles/soal/<?= $value['id_soal'] ?>/<?= $value[$pilgan['COLUMN_NAME']] ?>" class="img-thumbnail mb-1 ms-1" width="100" height="100">
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div>
        <?php endforeach; ?>
    </form>
    <div class="row mb-3 d-none" id="submit-pengerjaan">
        <div class="col">
            <center>
                <a href="javascript: void(0);" class="btn btn-primary" onclick="submitPengerjaan()">Selesai</a>
            </center>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <center>
                <?php for ($i = 1; $i <= count($soalAndJawaban); $i++) : ?>
                    <button class="btn btn-<?= is_null($soalAndJawaban[$i - 1]['jawaban']) ? 'secondary' : 'info' ?> btn-sm btn-paginate-soal ms-2 mb-2" id="paginate-soal-<?= $soalAndJawaban[$i - 1]['id_soal'] ?>" type="button" onclick="showSoal('<?= $soalAndJawaban[$i - 1]['id_soal'] ?>')"><?= sprintf("%02d", $i) ?></button>
                    <?php if ($i % 10 == 0) : ?>
                        <br>
                    <?php endif; ?>
                <?php endfor; ?>
            </center>
        </div>
    </div>
<?php elseif ($checkPengerjaan && $checkPengerjaan['status_pengerjaan'] == 1) : ?>
    <div class="row" id="preparation">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header bg-light-subtle">Pengerjaan Selesai</h5>
                <div class="card-body">
                    <h5 class="card-title">"Terima kasih telah mengerjakan soal psikotes ini, silahkan tunggu hasil penilaian dari kami."</h5>
                    <p class="card-text">
                        Anda telah menyelesaikan pengerjaan soal psikotes ini, silahkan menunggu hasil penilaian dari kami.
                    </p>
                    <a href="dashboard.php?mydashboard" class="btn btn-danger"> Kembali</a>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>
<?php else : ?>
    <div class="row" id="preparation">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header bg-light-subtle">Persiapan</h5>
                <div class="card-body">
                    <h5 class="card-title">"Tetap tenang dan fokuslah pada setiap soal dengan cermat ingatlah bahwa tes ini adalah kesempatan untuk menunjukkan kemampuan dan potensi terbaik Anda."</h5>
                    <p class="card-text">
                        Setelah tombol "Mulai" ditekan, waktu pengerjaan akan berjalan dan soal akan muncul.
                    </p>
                    <a href="javascript: void(0);" class="btn btn-primary" onclick="startExecution()">Mulai</a>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>
<?php endif; ?>
<script>
    $(document).ready(function() {
        localStorage.removeItem('id_soal');
        let firstSoalId = $('#firstSoalId').val();
        if ($('#checkPengerjaan').val() == 0) {
            showSoal(firstSoalId);
        }

        if ($('#checkPengerjaan').val() == 0) {
            countDown();
        }
    });

    function startExecution() {
        let user_id = $('#id_user').val();
        let waktu = $('#waktu').val();
        $.ajax({
            url: 'classes/Pengerjaan.php',
            type: 'POST',
            data: {
                user_id: user_id,
                waktu: waktu,
                action: 'startExecution'
            },
            success: function(data) {
                let response = JSON.parse(data);
                Swal.fire({
                    icon: response.status,
                    title: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            }
        });
    }

    function countDown() {
        let waktu = $('#waktu').val();
        let start_time = $('#start_time').val();
        let countDownDate = new Date(start_time * 1000 + (waktu * 60 * 1000)).getTime();
        let x = setInterval(function() {
            let now = new Date().getTime();
            let distance = countDownDate - now;
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);
            $('#countdown-timer').text(minutes + "m " + seconds + "s ");
            if (distance < 0) {
                clearInterval(x);
                $('#countdown-timer').text("Waktu Habis");
                otomatisSubmit();
            }
        }, 1000);
    }

    function showSoal(id_soal) {
        $('#execution-' + id_soal).removeClass('d-none');
        let id_soal_active = localStorage.getItem('id_soal');
        if (id_soal_active && id_soal_active != id_soal) {
            $('#execution-' + id_soal_active).addClass('d-none');
        }
        localStorage.setItem('id_soal', id_soal);
    }

    function answerThis(el, id_soal, nextSoalId) {
        let user_id = $('#id_user').val();
        let jawaban = $(el).val();
        $.ajax({
            url: 'classes/Pengerjaan.php',
            type: 'POST',
            data: {
                user_id: user_id,
                soal_id: id_soal,
                jawaban: jawaban,
                action: 'answerSoal'
            },
            success: function(data) {
                $('#paginate-soal-' + id_soal).removeClass('btn-secondary').addClass('btn-info');
                // setTimeout(() => {
                //     if (nextSoalId != null || nextSoalId != undefined || nextSoalId != '') {
                //         showSoal(nextSoalId);
                //         $('#submit-pengerjaan').removeClass('d-none');
                //     }
                // }, 500);
                if (nextSoalId == id_soal) {
                    $('#submit-pengerjaan').removeClass('d-none');
                }
            }
        });
    }

    function submitPengerjaan() {
        let formData = new FormData();
        let form = $('#form-pengerjaan-soal').serializeArray();
        let send = {};
        $.each(form, function(i, field) {
            formData.append(field.name, field.value);
        });
        let user_id = $('#id_user').val();
        let skor = $('#skor').val();
        formData.append('user_id', user_id);
        formData.append('skor', skor);
        formData.append('action', 'submitPengerjaan');
        $.ajax({
            url: 'classes/Pengerjaan.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Menyimpan Jawaban',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(data) {
                let response = JSON.parse(data);
                Swal.fire({
                    icon: response.status,
                    title: response.message,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timer: 2000
                }).then(() => {
                    location.reload();
                });
            }
        });
    }

    function otomatisSubmit() {
        Swal.fire({
            icon: 'warning',
            title: 'Waktu Habis',
            text: 'Waktu pengerjaan telah habis, jawaban akan disubmit secara otomatis',
            showConfirmButton: false,
            allowOutsideClick: false,
            timer: 5000,
            timerProgressBar: true
        }).then(() => {
            submitPengerjaan();
        });
    }
</script>