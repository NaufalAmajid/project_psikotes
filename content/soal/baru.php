<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Soal Baru</h4>
                <p class="text-muted mb-0">No Soal. <?= $_GET['nosoal'] ?></p>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <label for="nama_soal" class="col-2 col-form-label">Nama Bank Soal</label>
                    <div class="col-6">
                        <?php
                        $namaBankSoal = $soalClass->getBankSoalByNoSoal($_GET['nosoal']);
                        ?>
                        <input type="text" class="form-control" name="nama_soal" id="nama_soal" placeholder="nama bank soal ..." value="<?= $namaBankSoal ? $namaBankSoal['nama_soal'] : '' ?>">
                        <small class="form-text text-info mb-0">*boleh diisi boleh dikosongkan</small>
                    </div>
                </div>
                <hr />
                <div class="col-12">
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="row mb-3">
                                <label for="kategori_soal" class="col-4 col-form-label">Pilih Kategori Soal</label>
                                <div class="col-8">
                                    <select name="kategori_soal" id="kategori_soal" class="form-select" onchange="soalSelectCategory(this)">
                                        <option value="">Pilih Kategori Soal</option>
                                        <?php foreach ($soalClass->getAllKategori() as $kategori) : ?>
                                            <option value="<?= $kategori['id_kategori'] ?>"><?= ucwords($kategori['nama_kategori']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kategori_soal" class="col-4 col-form-label">Soal</label>
                                <div class="col-8">
                                    <textarea name="soal" id="soal" class="form-control" rows="5" placeholder="soal ..."></textarea>
                                </div>
                            </div>
                            <div class="row mb-3 d-none" id="soal-gambar">
                                <label for="gambar" class="col-4 col-form-label">Gambar</label>
                                <div class="col-8">
                                    <input type="file" class="form-control" id="gambar" name="gambar" multiple>
                                    <small class="form-text text-info">*anda dapat langsung melakukan upload beberapa file.</small>
                                </div>
                            </div>
                            <div class="row mb-3" id="pilihan-ganda">
                                <label class="col-4 col-form-label">Pilihan Ganda</label>
                                <div class="col-8">
                                    <?php
                                    $pilgan      = $soalClass->getPilgan();
                                    $alfaPilgan  = [];
                                    foreach ($pilgan as $pil) :
                                        $getLast = explode('_', $pil['COLUMN_NAME']);
                                        $alfaPilgan[] = $getLast[1];
                                    ?>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <label for="pilgan_<?= $getLast[1] ?>" class="col-form-label">Pilihan <?= $getLast[1] ?></label>
                                                <input type="text" class="form-control" name="pilgan_<?= $getLast[1] ?>" id="pilgan_<?= $getLast[1] ?>" placeholder="pilihan <?= $getLast[1] ?> ...">
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="kunci_jawaban" class="col-form-label">Kunci Jawaban</label>
                                            <select name="kunci_jawaban" id="kunci_jawaban" class="form-select">
                                                <option value="">Pilih Kunci Jawaban</option>
                                                <?php foreach ($alfaPilgan as $alfa) : ?>
                                                    <option value="<?= $alfa ?>">Pilihan <?= $alfa ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <button class="btn btn-primary" type="button" onclick="saveSoal('<?= $_GET['nosoal'] ?>', 'add')">Tambah Soal</button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row mb-3">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Soal</th>
                                                <th>Kategori</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $getAllSoal = $soalClass->getSoalByNoSoal($_GET['nosoal']);
                                            ?>
                                            <?php foreach ($getAllSoal as $so) : ?>
                                                <tr>
                                                    <td><?= $so['soal'] ?></td>
                                                    <td><?= $so['nama_kategori'] ?></td>
                                                    <td>
                                                        <a href="javascript: void(0);" class="text-primary fs-16 px-1"> <i class="ri-settings-3-line"></i></a>
                                                        <a href="javascript: void(0);" class="text-danger fs-16 px-1" onclick="deleteSoal('<?= $so['id_soal'] ?>')"> <i class="ri-delete-bin-2-line"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function soalSelectCategory(el) {
        let val = $(el).find('option:selected').val();
        if (val == 1) {
            $('#soal-gambar').removeClass('d-none');
            $('#pilihan-ganda').removeClass('d-none');
        } else {
            if (val == 2) {
                $('#pilihan-ganda').addClass('d-none');
            } else {
                $('#pilihan-ganda').removeClass('d-none');
            }
            $('#soal-gambar').addClass('d-none');
        }
    }

    function saveSoal(nosoal, status) {
        let formData = new FormData();
        let gambar = $('#gambar').prop('files');
        for (let i = 0; i < gambar.length; i++) {
            formData.append('gambar[]', gambar[i]);
        }
        let AlfaPilgan = <?= json_encode($alfaPilgan) ?>;
        let pilgan = [];
        for (let i = 0; i < AlfaPilgan.length; i++) {
            pilgan.push($('#pilgan_' + AlfaPilgan[i]).val());
        }
        let kunci_jawaban = $('#kunci_jawaban').find('option:selected').val();
        let nama_soal = $('#nama_soal').val();
        let soal = $('#soal').val();
        let kategori = $('#kategori_soal').find('option:selected').val();
        let no_soal = nosoal;
        formData.append('nama_soal', nama_soal);
        formData.append('soal', soal);
        formData.append('kategori_id', kategori);
        formData.append('no_soal', no_soal);
        formData.append('action', 'saveSoal');
        formData.append('status', status);
        formData.append('pilgan', JSON.stringify(pilgan));
        formData.append('kunci_jawaban', kunci_jawaban);
        $.ajax({
            url: 'classes/Soal.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
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

    function deleteSoal(id_soal) {
        $.ajax({
            url: 'classes/Soal.php',
            type: 'POST',
            data: {
                action: 'deleteSoal',
                id_soal: id_soal
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
        })
    }
</script>