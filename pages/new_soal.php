<?php
require_once 'classes/Soal.php';
$soalClass = new Soal();
?>
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li>
                            <a href="javascript:void(0)" class="btn btn-primary float-end" type="button" onclick="createNewSoal()"><i class="ri-draft-fill"></i> Buat Soal</a>
                        </li>
                    </ol>
                </div>
                <h4 class="page-title">Bank Soal</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <?php
        $soals = $soalClass->getAllSoal();
        $no = 1;
        ?>
        <?php foreach ($soals as $soal) : ?>
            <div class="col-xl-6 col-sm-12 ">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <div class="card-widgets">
                            <a href="javascript:void(0)" onclick="saveSoal('<?= $soal['id_soal'] ?>','<?= $soal['kategori_id'] ? 'edit' : 'add' ?>')"><i class="ri-save-2-line"></i></a>
                            <a data-bs-toggle="collapse" class="btn-card-collapse" href="#card-collapse-<?= $soal['id_soal'] ?>"><i class="ri-subtract-line"></i></a>
                            <a href="javascript:void(0)" onclick="deleteSoal('<?= $soal['id_soal'] ?>')"><i class="ri-close-line"></i></a>
                        </div>
                        <h5 class="card-title mb-0">Soal <?= $no++ ?></h5>
                    </div>
                    <div id="card-collapse-<?= $soal['id_soal'] ?>" class="collapse show">
                        <div class="card-body">
                            <form id="form-soal-<?= $soal['id_soal'] ?>">
                                <div class="col-12">
                                    <div class="row mb-1">
                                        <label for="kategori_soal" class="col-4 col-form-label col-form-label-sm">Kategori</label>
                                        <div class="col-8">
                                            <select name="kategori_soal" id="kategori_soal_<?= $soal['id_soal'] ?>" class="form-select form-select-sm" onchange="soalSelectCategory(this, '<?= $soal['id_soal'] ?>')" <?= is_null($soal['kategori_id']) ? '' : 'disabled' ?>>
                                                <option value="">Kategori Soal</option>
                                                <?php foreach ($soalClass->getAllKategori() as $kategori) : ?>
                                                    <option value="<?= $kategori['id_kategori'] ?>" <?= $soal['kategori_id'] == $kategori['id_kategori'] ? 'selected' : '' ?>>
                                                        <?= ucwords($kategori['nama_kategori']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <label for="soal" class="col-4 col-form-label col-form-label-sm">Soal</label>
                                        <div class="col-8">
                                            <textarea name="soal" id="soal" class="form-control form-control-sm" rows="2" placeholder="..."><?= nl2br($soal['soal']) ?></textarea>
                                        </div>
                                    </div>
                                    <?php if ($soal['kategori_id'] == 1 || $soal['kategori_id'] == '') : ?>
                                        <?php if ($soal['kategori_id'] == '') : ?>
                                            <div class="row mb-1 <?= $soal['kategori_id'] ? '' : 'd-none' ?>" id="soal-gambar-<?= $soal['id_soal'] ?>">
                                                <label for="gambar" class="col-4 col-form-label col-form-label-sm">Gambar</label>
                                                <div class="col-8">
                                                    <input type="file" class="form-control form-control-sm" id="gambar-<?= $soal['id_soal'] ?>" name="gambar-<?= $soal['id_soal'] ?>" multiple>
                                                    <small class="text-muted fs-6">*upload beberapa file.</small>
                                                </div>
                                            </div>
                                        <?php else : ?>
                                            <?php
                                            $fileSoal = json_decode($soal['file'], true);
                                            ?>
                                            <div class="row mb-1 <?= $soal['kategori_id'] ? '' : 'd-none' ?>" id="soal-gambar-<?= $soal['id_soal'] ?>">
                                                <label for="gambar" class="col-4 col-form-label col-form-label-sm">Gambar</label>
                                                <div class="col-8">
                                                    <?php foreach ($fileSoal as $file) : ?>
                                                        <img src="myfiles/soal/<?= $soal['id_soal'] ?>/<?= $file ?>" class="img-thumbnail mb-1 ms-1" width="100" height="1000">
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php
                                    $kategoriSoalPilgan = [1, 3];
                                    if (in_array($soal['kategori_id'], $kategoriSoalPilgan) || $soal['kategori_id'] == '') :
                                    ?>
                                        <div class="row mb-1" id="pilihan-ganda-<?= $soal['id_soal'] ?>">
                                            <div class="col-12">
                                                <div class="row mb-2">
                                                    <?php
                                                    $pilgan = $soalClass->getPilgan();
                                                    $alfaPilgan = [];
                                                    foreach ($pilgan as $pil) :
                                                        $getLast = explode('_', $pil['COLUMN_NAME']);
                                                        $alfaPilgan[] = $getLast[1];
                                                    ?>
                                                        <div class="col-6">
                                                            <label for="pilgan_<?= $getLast[1] ?>" class="col-form-label col-form-label-sm"><?= strtoupper($getLast[1]) ?></label>
                                                            <?php if ($soal['kategori_id'] == 3 || $soal['kategori_id'] == '') : ?>
                                                                <div class="input-for-pilgan-<?= $soal['id_soal'] ?>">
                                                                    <input type="text" class="form-control form-control-sm" name="pilgan_<?= $getLast[1] ?>_<?= $soal['id_soal'] ?>" id="pilgan_<?= $getLast[1] ?>_<?= $soal['id_soal'] ?>" placeholder="..." value="<?= $soal[$pil['COLUMN_NAME']] ?>">
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if ($soal['kategori_id'] == 1 || $soal['kategori_id'] == '') : ?>
                                                                <div class="file-for-pilgan-<?= $soal['id_soal'] ?> <?= is_null($soal['kategori_id']) ? 'd-none' : '' ?>">
                                                                    <?php if (is_null($soal['kategori_id'])) : ?>
                                                                        <img src="assets/images/file-placeholder.png" onclick="triggerClick(this, '<?= $getLast[1] ?>', '<?= $soal['id_soal'] ?>')" alt="image-placeholder" id="image-placeholder-<?= $getLast[1] ?>-<?= $soal['id_soal'] ?>" class="img-thumbnail mb-1 ms-1" width="50" height="50">
                                                                        <input type="file" class="form-control form-control-sm d-none input-file-pilgan-<?= $soal['id_soal'] ?>" onchange="displayImage(this, '<?= $getLast[1] ?>', '<?= $soal['id_soal'] ?>')" name="pilgan_file_<?= $getLast[1] ?>_<?= $soal['id_soal'] ?>" id="pilgan_file_<?= $getLast[1] ?>_<?= $soal['id_soal'] ?>">
                                                                    <?php else : ?>
                                                                        <img src="myfiles/soal/<?= $soal['id_soal'] ?>/<?= $soal[$pil['COLUMN_NAME']] ?>" class="img-thumbnail mb-1 ms-1" width="100" height="100">
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-1" id="pilgan-kunci-<?= $soal['id_soal'] ?>">
                                            <div class="col-12">
                                                <div class="row mb-2">
                                                    <label for="kunci-<?= $soal['id_soal'] ?>" class="col-4 col-form-label col-form-label-sm">Kunci Jawaban</label>
                                                    <div class="col-8">
                                                        <select name="kunci-<?= $soal['id_soal'] ?>" id="kunci-<?= $soal['id_soal'] ?>" class="form-select form-select-sm">
                                                            <option value="">Kunci Jawaban</option>
                                                            <?php foreach ($alfaPilgan as $alfa) : ?>
                                                                <option value="<?= $alfa ?>" <?= $soal['kunci_jawaban'] == $alfa ? 'selected' : '' ?>><?= strtoupper($alfa) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script>
    $(document).ready(function() {
        let collapse = document.querySelectorAll('.btn-card-collapse');
        for (let i = 0; i < collapse.length - 1; i++) {
            collapse[i].click();
        }
    });

    function triggerClick(el, alfa, id_soal) {
        $(`#pilgan_file_${alfa}_${id_soal}`).click();
    }

    function displayImage(e, alfa, id_soal) {
        if (e.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector(`#image-placeholder-${alfa}-${id_soal}`).setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(e.files[0]);
        }
    }

    function soalSelectCategory(el, id_soal) {
        let val = $(el).find('option:selected').val();
        let inputForPilgan = document.querySelectorAll(`.input-for-pilgan-${id_soal}`);
        let fileForPilgan = document.querySelectorAll(`.file-for-pilgan-${id_soal}`);
        let inputFilePilgan = document.querySelectorAll(`.input-file-pilgan-${id_soal}`);

        if (val == 1) {
            $(`#soal-gambar-${id_soal}`).removeClass('d-none');
            $(`#pilihan-ganda-${id_soal}`).removeClass('d-none');
            $(`#pilgan-kunci-${id_soal}`).removeClass('d-none');
            for (let i = 0; i < inputForPilgan.length; i++) {
                inputForPilgan[i].classList.add('d-none');
                fileForPilgan[i].classList.remove('d-none');
            }
        } else {
            if (val == 2) {
                $(`#pilihan-ganda-${id_soal}`).addClass('d-none');
                $(`#pilgan-kunci-${id_soal}`).addClass('d-none');
            } else {
                $(`#pilihan-ganda-${id_soal}`).removeClass('d-none');
                $(`#pilgan-kunci-${id_soal}`).removeClass('d-none');
                for (let i = 0; i < inputForPilgan.length; i++) {
                    inputForPilgan[i].classList.remove('d-none');
                    fileForPilgan[i].classList.add('d-none');
                }
            }
            $(`#soal-gambar-${id_soal}`).addClass('d-none');
        }
    }

    function createNewSoal() {
        $.ajax({
            url: 'classes/Soal.php',
            type: 'post',
            data: {
                'action': 'createNewSoal'
            },
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

    async function getAllPilgan() {
        try {
            let response = await $.ajax({
                url: 'classes/Soal.php',
                type: 'post',
                data: {
                    'action': 'getAllPilgan'
                }
            });
            return JSON.parse(response);
        } catch (error) {
            console.log(error);
            throw error;
        }
    }

    async function saveSoal(id_soal, status) {
        let formData = new FormData();
        let form = $(`#form-soal-${id_soal}`).serializeArray();
        if (status == 'edit') {
            form.push({
                name: 'kategori_soal',
                value: $(`#kategori_soal_${id_soal}`).find('option:selected').val()
            });
        }
        let send = {};
        for (let i = 0; i < form.length; i++) {
            send[form[i]['name']] = form[i]['value'];
        }
        send['id_soal'] = id_soal;
        formData.append('data', JSON.stringify(send));
        if (status == 'add') {
            let gambar = $(`#gambar-${id_soal}`).prop('files');
            for (let i = 0; i < gambar.length; i++) {
                formData.append('gambar[]', gambar[i]);
            }
            let pilganFiles = await getAllPilgan();
            pilganFiles.forEach(pilgan => {
                let pilganFile = $(`#pilgan_file_${pilgan}_${id_soal}`).prop('files')[0];
                if (pilganFile) {
                    formData.append(`pilgan_file_${pilgan}_${id_soal}`, pilganFile);
                }
            });
        }
        formData.append('id_soal', id_soal);
        formData.append('action', 'saveSoalNew');
        $.ajax({
            url: 'classes/Soal.php',
            type: 'post',
            data: formData,
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

    function deleteSoal(id_soal) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Soal yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Keluar!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'classes/Soal.php',
                    type: 'post',
                    data: {
                        'action': 'deleteSoalNew',
                        'id_soal': id_soal
                    },
                    success: function(response) {
                        console.log(response);
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
        });
    }
</script>