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
                            <a href="javascript:void(0)" onclick="saveSoal('<?= $soal['id_soal'] ?>')"><i class="ri-save-2-line"></i></a>
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
                                            <select name="kategori_soal" id="kategori_soal" class="form-select form-select-sm" onchange="soalSelectCategory(this, '<?= $soal['id_soal'] ?>')">
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
                                    <div class="row mb-1 d-none" id="soal-gambar-<?= $soal['id_soal'] ?>">
                                        <label for="gambar" class="col-4 col-form-label col-form-label-sm">Gambar</label>
                                        <div class="col-8">
                                            <input type="file" class="form-control form-control-sm" id="gambar-<?= $soal['id_soal'] ?>" name="gambar-<?= $soal['id_soal'] ?>" multiple>
                                            <small class="text-muted fs-6">*upload beberapa file.</small>
                                        </div>
                                    </div>
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
                                                        <div class="input-for-pilgan-<?= $soal['id_soal'] ?>">
                                                            <input type="text" class="form-control form-control-sm" name="pilgan_<?= $getLast[1] ?>_<?= $soal['id_soal'] ?>" id="pilgan_<?= $getLast[1] ?>_<?= $soal['id_soal'] ?>" placeholder="...">
                                                        </div>
                                                        <div class="file-for-pilgan-<?= $soal['id_soal'] ?> d-none">
                                                            <img src="assets/images/file-placeholder.png" onclick="triggerClick(this, '<?= $getLast[1] ?>', '<?= $soal['id_soal'] ?>')" alt="image-placeholder" id="image-placeholder-<?= $getLast[1] ?>-<?= $soal['id_soal'] ?>" class="img-thumbnail mb-1 ms-1" width="50" height="50">
                                                            <input type="file" class="form-control form-control-sm d-none input-file-pilgan-<?= $soal['id_soal'] ?>" onchange="displayImage(this, '<?= $getLast[1] ?>', '<?= $soal['id_soal'] ?>')" name="pilgan_file_<?= $getLast[1] ?>_<?= $soal['id_soal'] ?>" id="pilgan_file_<?= $getLast[1] ?>_<?= $soal['id_soal'] ?>">
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
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
            for (let i = 0; i < inputForPilgan.length; i++) {
                inputForPilgan[i].classList.add('d-none');
                fileForPilgan[i].classList.remove('d-none');
            }
        } else {
            if (val == 2) {
                $(`#pilihan-ganda-${id_soal}`).addClass('d-none');
            } else {
                $(`#pilihan-ganda-${id_soal}`).removeClass('d-none');
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

    function getAllPilgan(callback) {
        $.ajax({
            url: 'classes/Soal.php',
            type: 'post',
            data: {
                'action': 'getAllPilgan'
            },
            success: function(response) {
                let data = JSON.parse(response);
                callback(data);
            }
        });
    }

    function saveSoal(id_soal) {
        let formData = new FormData();
        let form = $(`#form-soal-${id_soal}`).serializeArray();
        let send = {};
        for (let i = 0; i < form.length; i++) {
            send[form[i]['name']] = form[i]['value'];
        }
        send['id_soal'] = id_soal;
        formData.append('data', JSON.stringify(send));
        let gambar = $(`#gambar-${id_soal}`).prop('files');
        for (let i = 0; i < gambar.length; i++) {
            formData.append('gambar[]', gambar[i]);
            console.log(gambar[i]);
        }

        let pilganFiles = {};
        getAllPilgan(function(callback) {
            // output ['a', 'b', 'c', 'd']
            let pilgan = callback;
            for (let i = 0; i < pilgan.length; i++) {
                let pilganFile = $(`#pilgan_file_${pilgan[i]}_${id_soal}`).prop('files')[0];
                if (pilganFile) {
                    pilganFiles[pilgan[i]] = pilganFile;
                }
            }
        })
        
        formData.append('action', 'saveSoalNew');
        $.ajax({
            url: 'classes/Soal.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                // let data = JSON.parse(response);
                // Swal.fire({
                //     icon: data.status,
                //     title: data.message,
                //     showConfirmButton: false,
                //     timer: 1500
                // }).then(() => {
                //     location.reload();
                // });
            }
        });
    }
</script>