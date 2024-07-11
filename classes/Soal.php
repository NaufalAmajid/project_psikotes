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
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    require_once '../config/connection.php';
    require_once '../classes/DB.php';

    $soalClass = new Soal();

    if ($_POST['action'] == 'saveSoal') {
        $nama_soal      = $_POST['nama_soal'];
        $no_soal        = $_POST['no_soal'];
        $soal           = $_POST['soal'];
        $kategori_id    = $_POST['kategori_id'];
        $pilgans         = json_decode($_POST['pilgan'], true);

        if (isset($_FILES['gambar'])) {
            // check new directory for gambar soal
            $gambar_new_destination = '../myfiles/soal/';
            if (!is_dir($gambar_new_destination)) {
                mkdir($gambar_new_destination, 0777, true);
            }

            $errors     = [];
            $newGambars = [];
            $gambars    = $_FILES['gambar'];

            // manage gambars
            foreach ($gambars['name'] as $key => $filename) {
                // get file properties
                $fileTmp        = $gambars['tmp_name'][$key];
                $fileType       = $gambars['type'][$key];
                $fileSize       = $gambars['size'][$key];
                $fileError      = $gambars['error'][$key];
                $fileExt        = explode('.', $filename);
                $fileActualExt  = strtolower(end($fileExt));
                $fileAllowed    = ['jpg', 'jpeg', 'png'];

                // check file extension
                if (in_array($fileActualExt, $fileAllowed)) {
                    if ($fileError === 0) {
                        if ($fileSize < 1000000) {
                            // create new name
                            $newFilename        = $no_soal . '_' . $key . '.' . $fileActualExt;
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
                echo json_encode(['status' => 'error', 'message' => $errors]);
                exit;
            }

            // merge name to save with format json
            $mergeNameGambars = [];
            foreach ($newGambars as $gambar) {
                $mergeNameGambars[] = $gambar['nama_gambar'];
            }
            $nama_gambar = json_encode($mergeNameGambars);
        }

        // check bank soal
        $bank_soal = $soalClass->getBankSoalByNoSoal($no_soal);
        $dataBankSoal = [
            'nama_soal'    => $nama_soal ? $nama_soal : 'Bank Soal ' . $no_soal,
            'no_soal'      => $no_soal,
        ];
        if ($bank_soal) {
            $soalClass->updateSoal('bank_soal', $dataBankSoal, ['no_soal' => $no_soal]);
        } else {
            $soalClass->saveSoal('bank_soal', $dataBankSoal);
        }

        // manage soal
        $dataSoal = [
            'no_soal'       => $no_soal,
            'soal'          => $soal,
            'kategori_id'   => $kategori_id,
        ];

        // soal pilihan ganda
        $getAlfaPilgan = $soalClass->getPilgan();
        $alfaPilgan = [];
        foreach ($getAlfaPilgan as $pil) {
            $getLast = explode('_', $pil['COLUMN_NAME']);
            $alfaPilgan[] = $getLast[1];
        }
        foreach ($alfaPilgan as $key => $alfa) {
            $dataSoal['jawaban_' . $alfa] = $pilgans[$key] ?: NULL;
        }
        $dataSoal['kunci_jawaban'] = $_POST['kunci_jawaban'] ?: NULL;

        // check gambar
        if (isset($nama_gambar)) {
            $dataSoal['file'] = $nama_gambar;
            foreach ($newGambars as $gambar) {
                move_uploaded_file($gambar['path_gambar'], $gambar['destination']);
            }
        }

        // check status save soal
        if ($_POST['status'] == 'add') {
            $save = $soalClass->saveSoal('soal', $dataSoal);
        } else {
            $save = $soalClass->updateSoal('soal', $dataSoal, ['id_soal' => $_POST['id_soal']]);
        }

        if ($save) {
            echo json_encode(['status' => 'success', 'message' => 'Soal berhasil disimpan!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Soal gagal disimpan!']);
        }
    }

    if ($_POST['action'] == 'deleteSoal') {
        $id_soal = $_POST['id_soal'];
        $detailSoal = $soalClass->getSoalById($id_soal);

        // check gambar
        if ($detailSoal['file']) {
            $gambar_new_destination = '../myfiles/soal/';
            $nama_gambar = json_decode($detailSoal['file'], true);
            foreach ($nama_gambar as $gambar) {
                $fileDestination = $gambar_new_destination . $gambar;
                if (file_exists($fileDestination)) {
                    unlink($fileDestination);
                }
            }
        }

        $delete = $soalClass->deleteSoal('soal', ['id_soal' => $id_soal]);
        if ($delete) {
            echo json_encode(['status' => 'success', 'message' => 'Soal berhasil dihapus!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Soal gagal dihapus!']);
        }
    }
}
