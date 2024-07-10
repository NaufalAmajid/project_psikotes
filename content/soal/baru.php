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
                                    <select name="kategori_soal" id="kategori_soal" class="form-select">
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
                            <div class="row mb-3">
                                <button class="btn btn-primary" type="button">Tambah Soal</button>
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