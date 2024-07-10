<?php

class Soal
{
    private $conn;

    public function __construct()
    {
        $this->conn = DB::getInstance()->connection();
    }

    public function getAllKategori()
    {
        $query = "SELECT * FROM kategori_soal";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    require_once '../config/connection.php';
    require_once '../classes/DB.php';

    $soal = new Soal();

    if ($_POST['action'] == 'saveSoal') {
        $nama_soal      = $_POST['nama_soal'];
        $no_soal        = $_POST['no_soal'];
        $soal           = $_POST['soal'];
        $kategori_id    = $_POST['kategori_id'];

        if (isset($_FILES['gambar'])) {
            // check new directory for gambar soal
            $gambar_new_destination = '../lampiran/soal/';
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
                    $errors[] = $filename . ' Didukung \n';
                } else {
                    $errors[] = $filename . ' Format file tidak didukung! Hanya JPG, JPEG, PNG! \n';
                }
            }
            echo json_encode($errors);
        } else {
            echo json_encode('no file');
        }
    }
}
