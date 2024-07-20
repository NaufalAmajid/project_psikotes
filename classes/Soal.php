<?php

class Soal
{
    private $conn;

    public function __construct()
    {
        $this->conn = DB::getInstance()->connection();
    }

    public function updateSoal($table, $data, $where)
    {
        $db = DB::getInstance();
        $res = $db->update($table, $data, $where);
        return $res;
    }

    public function saveSoal($table, $data)
    {
        $db = DB::getInstance();
        $res = $db->add($table, $data);
        return $res;
    }

    public function deleteSoal($table, $where)
    {
        $db = DB::getInstance();
        $res = $db->delete($table, $where);
        return $res;
    }

    public function getPilgan()
    {
        $query = "select
                    COLUMN_NAME
                from
                    INFORMATION_SCHEMA.COLUMNS
                where
                    TABLE_NAME = 'soal'
                    and COLUMN_NAME like 'jawaban_%'";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllKategori()
    {
        $query = "SELECT * FROM kategori_soal";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSoal()
    {
        $query = "SELECT 
                * 
                FROM 
                soal so 
                LEFT JOIN kategori_soal ks ON so.kategori_id = ks.id_kategori 
                ORDER BY 
                so.id_soal ASC";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBankSoalByNoSoal($no_soal)
    {
        $query = "SELECT * FROM bank_soal WHERE no_soal = :no_soal";
        $result = $this->conn->prepare($query);
        $result->bindParam(':no_soal', $no_soal);
        $result->execute();

        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllBankSoal()
    {
        $query = "select
                    bs.id_bank,
                    bs.no_soal,
                    bs.nama_soal,
                    count(so.id_soal) as jumlah_soal
                from
                    bank_soal bs
                left join soal so on
                    bs.no_soal = so.no_soal
                where
                    bs.status_bank = 1
                group by
                    bs.id_bank,
                    bs.no_soal,
                    bs.nama_soal";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSoalByNoSoal($no_soal)
    {
        $query = "select
                        *
                    from
                        soal so
                    join kategori_soal ks on
                        so.kategori_id = ks.id_kategori
                    where
                        so.no_soal = :no_soal";
        $result = $this->conn->prepare($query);
        $result->bindParam(':no_soal', $no_soal);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSoalById($id_soal)
    {
        $query = "select
                        *
                    from
                        soal so
                    join kategori_soal ks on
                        so.kategori_id = ks.id_kategori
                    where
                        so.id_soal = :id_soal";
        $result = $this->conn->prepare($query);
        $result->bindParam(':id_soal', $id_soal);
        $result->execute();

        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return false;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        $items = array_diff(scandir($dir), ['.', '..']);
        foreach ($items as $item) {
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        return rmdir($dir);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    require_once '../config/connection.php';
    require_once '../classes/DB.php';

    $soalClass = new Soal();

    if ($_POST['action'] == 'createNewSoal') {
        $no_soal = date('YmdHis');
        $newSoal = $soalClass->saveSoal('soal', ['no_soal' => $no_soal]);
        if ($newSoal) {
            echo json_encode(['status' => 'success', 'message' => 'Soal baru berhasil dibuat!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Soal baru gagal dibuat!']);
        }
    }

    if ($_POST['action'] == 'getAllPilgan') {
        $pilgans = $soalClass->getPilgan();
        $alfaPilgan = [];
        foreach ($pilgans as $pil) {
            $getLast = explode('_', $pil['COLUMN_NAME']);
            $alfaPilgan[] = $getLast[1];
        }
        echo json_encode($alfaPilgan);
    }

    if ($_POST['action'] == 'saveSoalNew') {
        $id_soal        = $_POST['id_soal'];
        $dataReq        = json_decode($_POST['data'], true);
        $soal           = $dataReq['soal'];
        $kategori_id    = $dataReq['kategori_soal'];
        $dataInsert     = [
            'soal'          => $soal,
            'kategori_id'   => $kategori_id,
            'kunci_jawaban' => isset($dataReq['kunci-' . $id_soal]) ? $dataReq['kunci-' . $id_soal] : NULL
        ];
        $pilgans = $soalClass->getPilgan();
        $alfaPilgan = [];
        foreach ($pilgans as $pil) {
            $getLast = explode('_', $pil['COLUMN_NAME']);
            $alfaPilgan[] = $getLast[1];
        }

        // check kategori_id
        if ($kategori_id == '') {
            echo json_encode(['status' => 'error', 'message' => 'Kategori soal belum dipilih!']);
            exit;
        }

        if ($kategori_id == 1) {
            if (isset($_FILES)) {
                if (isset($_FILES['gambar'])) {
                    // MANAGE GAMBAR SOAL
                    // check new directory for gambar soal
                    $gambar_new_destination = '../myfiles/soal/' . $id_soal . '/';
                    if (!is_dir($gambar_new_destination)) {
                        mkdir($gambar_new_destination, 0777, true);
                    }

                    $errors     = [];
                    $newGambars = [];
                    $gambars    = $_FILES['gambar'];
                    foreach ($gambars['name'] as $key => $filename) {
                        // get file properties
                        $fileTmp        = $gambars['tmp_name'][$key];
                        $fileType       = $gambars['type'][$key];
                        $fileSize       = $gambars['size'][$key];
                        $fileError      = $gambars['error'][$key];
                        $fileExt        = explode('.', $filename);
                        $fileActualExt  = strtolower(end($fileExt));
                        $fileAllowed    = ['jpg', 'jpeg', 'png'];
                        if (in_array($fileActualExt, $fileAllowed)) {
                            if ($fileError === 0) {
                                if ($fileSize < 1000000) {
                                    // create new name
                                    $newFilename        = $id_soal . '_' . uniqid('', true) . '.' . $fileActualExt;
                                    $fileDestination    = $gambar_new_destination . $newFilename;
                                    $newGambars[]       = [
                                        'nama_gambar'   => $newFilename,
                                        'path_gambar'   => $fileTmp,
                                        'destination'   => $fileDestination
                                    ];
                                } else {
                                    $errors[] = $filename . ' Ukuran file terlalu besar! \n';
                                }
                            } else {
                                $errors[] = $filename . ' Terjadi kesalahan saat mengunggah file! \n';
                            }
                        } else {
                            $errors[] = $filename . ' Format file tidak didukung! Hanya JPG, JPEG, PNG! \n';
                        }
                    }

                    // check error
                    if (count($errors) > 0) {
                        $errors = implode('', $errors);
                        echo json_encode(['status' => 'error', 'message' => $errors]);
                        exit;
                    }

                    // merge name to save with format json
                    $mergeNameGambars = [];
                    foreach ($newGambars as $gambar) {
                        $mergeNameGambars[] = $gambar['nama_gambar'];
                    }
                    $nama_gambar = json_encode($mergeNameGambars);
                    $dataInsert['file'] = $nama_gambar;
                    // END MANAGE GAMBAR SOAL

                    // MANAGE GAMBAR PILIHAN GANDA
                    $issetFilePilgan = [];
                    $notIssetFilePilgan = [];
                    foreach ($alfaPilgan as $alfa) {
                        if (isset($_FILES['pilgan_file_' . $alfa . '_' . $id_soal])) {
                            $issetFilePilgan[$alfa] = $_FILES['pilgan_file_' . $alfa . '_' . $id_soal];
                        } else {
                            $notIssetFilePilgan[] = $alfa;
                        }
                    }

                    if (count($notIssetFilePilgan) > 0) {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Gambar pilihan ganda ' . implode(', ', $notIssetFilePilgan) . ' tidak boleh kosong!'
                        ]);
                        exit;
                    }

                    $errorsPilgan = [];
                    $newGambarsPilgan = [];
                    foreach ($issetFilePilgan as $alfa => $gambars) {
                        // get file properties
                        $fileTmp        = $gambars['tmp_name'];
                        $fileType       = $gambars['type'];
                        $fileSize       = $gambars['size'];
                        $fileError      = $gambars['error'];
                        $fileExt        = explode('.', $gambars['name']);
                        $fileActualExt  = strtolower(end($fileExt));
                        $fileAllowed    = ['jpg', 'jpeg', 'png'];
                        if (in_array($fileActualExt, $fileAllowed)) {
                            if ($fileError === 0) {
                                if ($fileSize < 1000000) {
                                    // create new name
                                    $newFilename        = $id_soal . '_' . $alfa . '.' . $fileActualExt;
                                    $fileDestination    = $gambar_new_destination . $newFilename;
                                    $newGambarsPilgan[] = [
                                        'nama_gambar'   => $newFilename,
                                        'path_gambar'   => $fileTmp,
                                        'destination'   => $fileDestination
                                    ];
                                } else {
                                    $errorsPilgan[] = $gambars['name'] . ' Ukuran file terlalu besar! \n';
                                }
                            } else {
                                $errorsPilgan[] = $gambars['name'] . ' Terjadi kesalahan saat mengunggah file! \n';
                            }
                        } else {
                            $errorsPilgan[] = $gambars['name'] . ' Format file tidak didukung! Hanya JPG, JPEG, PNG! \n';
                        }
                    }

                    // inv new gambars pilgan to data insert
                    foreach ($newGambarsPilgan as $keyGambarPilgan => $gambarPilgan) {
                        $dataInsert['jawaban_' . $alfaPilgan[$keyGambarPilgan]] = $gambarPilgan['nama_gambar'];
                    }

                    // check error
                    if (count($errorsPilgan) > 0) {
                        $errorsPilgan = implode('', $errorsPilgan);
                        echo json_encode(['status' => 'error', 'message' => $errorsPilgan]);
                        exit;
                    }

                    // check kunci jawaban
                    if ($dataInsert['kunci_jawaban'] == '') {
                        echo json_encode(['status' => 'error', 'message' => 'Kunci jawaban tidak boleh kosong!']);
                        exit;
                    }
                    // END MANAGE GAMBAR PILIHAN GANDA

                    // SAVE SOAL TO DATABASE
                    $save = $soalClass->updateSoal('soal', $dataInsert, ['id_soal' => $id_soal]);
                    if ($save) {
                        foreach ($newGambars as $gambar) {
                            move_uploaded_file($gambar['path_gambar'], $gambar['destination']);
                        }
                        foreach ($newGambarsPilgan as $gambar) {
                            move_uploaded_file($gambar['path_gambar'], $gambar['destination']);
                        }
                        echo json_encode(['status' => 'success', 'message' => 'Soal berhasil disimpan!']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Soal gagal disimpan!']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Gambar soal tidak boleh kosong!']);
                    exit;
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gambar soal tidak boleh kosong!']);
                exit;
            }
        } else {

            foreach ($alfaPilgan as $alfa) {
                $dataInsert['jawaban_' . $alfa] = $dataReq['pilgan_' . $alfa . '_' . $id_soal] ?: NULL;
            }
            // check kunci jawaban
            if ($dataInsert['kunci_jawaban'] == '') {
                echo json_encode(['status' => 'error', 'message' => 'Kunci jawaban tidak boleh kosong!']);
                exit;
            }

            // save soal to database
            $save = $soalClass->updateSoal('soal', $dataInsert, ['id_soal' => $id_soal]);

            if ($save) {
                echo json_encode(['status' => 'success', 'message' => 'Soal berhasil disimpan!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Soal gagal disimpan!']);
            }
        }
    }

    if ($_POST['action'] == 'deleteSoalNew') {
        $id_soal = $_POST['id_soal'];
        $detailSoal = $soalClass->getSoalById($id_soal);

        // check gambar
        $detDirectory = '../myfiles/soal/' . $id_soal . '/';
        if (is_dir($detDirectory)) {
            $soalClass->deleteDirectory($detDirectory);
        }

        $delete = $soalClass->deleteSoal('soal', ['id_soal' => $id_soal]);
        if ($delete) {
            echo json_encode(['status' => 'success', 'message' => 'Soal berhasil dihapus!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Soal gagal dihapus!']);
        }
    }
}
