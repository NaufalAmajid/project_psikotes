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
                        <input type="text" class="form-control" name="nama_soal" id="nama_soal" placeholder="nama bank soal ...">
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
                                        <?php foreach ($soal->getAllKategori() as $kategori) : ?>
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
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                            </tr>
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
        } else {
            $('#soal-gambar').addClass('d-none');
        }
    }

    function saveSoal(nosoal, status) {
        let formData = new FormData();
        let gambar = $('#gambar').prop('files');
        for (let i = 0; i < gambar.length; i++) {
            formData.append('gambar[]', gambar[i]);
        }
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
        $.ajax({
            url: 'classes/Soal.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                console.log(data);
            }
        });
    }
</script>