<?php

date_default_timezone_set('Asia/Jakarta');

class Pengerjaan
{
    private $conn;

    public function __construct()
    {
        $this->conn = DB::getInstance()->connection();
    }

    public function updatePengerjaan($table, $data, $where)
    {
        $db = DB::getInstance();
        $res = $db->update($table, $data, $where);
        return $res;
    }

    public function addPengerjaan($table, $data)
    {
        $db = DB::getInstance();
        $res = $db->add($table, $data);
        return $res;
    }

    public function getPengerjaanByIdUser($user_id)
    {
        $query = "SELECT * FROM pengerjaan WHERE user_id = $user_id";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function soalAndJawabanByUserId($user_id)
    {
        $query = "SELECT
                    *
                    FROM
                    soal so
                    left join jawaban jaw on jaw.soal_id = so.id_soal and jaw.user_id = $user_id
                    left join kategori_soal ks on so.kategori_id = ks.id_kategori
                    ORDER BY
                    so.id_soal ASC";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
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

    public function checkJawaban($user_id, $soal_id)
    {
        $query = "SELECT * FROM jawaban WHERE user_id = $user_id AND soal_id = $soal_id";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetch(PDO::FETCH_ASSOC);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    session_start();
    require_once '../config/connection.php';
    require_once '../classes/DB.php';

    $pengerjaan = new Pengerjaan();
    if ($_POST['action'] == 'startExecution') {
        $dataInsert = [
            'user_id' => $_POST['user_id'],
            'waktu' => $_POST['waktu']
        ];

        $where = [
            'user_id' => $_POST['user_id']
        ];

        $checkPengerjaan = $pengerjaan->getPengerjaanByIdUser($_POST['user_id']);
        if ($checkPengerjaan) {
            $res = $pengerjaan->updatePengerjaan('pengerjaan', $dataInsert, $where);
        } else {
            $res = $pengerjaan->addPengerjaan('pengerjaan', $dataInsert);
        }

        if ($res) {
            echo json_encode(['status' => 'success', 'message' => 'Pengerjaan berhasil dimulai']);
        } else {
            echo json_encode(['status' => 'failed', 'message' => 'Pengerjaan gagal dimulai']);
        }
    }

    if ($_POST['action'] == 'answerSoal') {
        $checkJawaban = $pengerjaan->checkJawaban($_POST['user_id'], $_POST['soal_id']);
        $dataInsert = [
            'user_id' => $_POST['user_id'],
            'soal_id' => $_POST['soal_id'],
            'jawaban' => $_POST['jawaban']
        ];

        $where = [
            'user_id' => $_POST['user_id'],
            'soal_id' => $_POST['soal_id']
        ];

        if ($checkJawaban) {
            $res = $pengerjaan->updatePengerjaan('jawaban', $dataInsert, $where);
        } else {
            $res = $pengerjaan->addPengerjaan('jawaban', $dataInsert);
        }

        if ($res) {
            echo json_encode(['status' => 'success', 'message' => 'Jawaban berhasil disimpan']);
        } else {
            echo json_encode(['status' => 'failed', 'message' => 'Jawaban gagal disimpan']);
        }
    }

    if ($_POST['action'] == 'submitPengerjaan') {
        sleep(3);
        // all data post
        $user_id = $_POST['user_id'];
        $penilaian = $_POST['penilaian'];
        $dataSoal = $pengerjaan->soalAndJawabanByUserId($_POST['user_id']);
        $dataPengerjaan = $pengerjaan->getPengerjaanByIdUser($_POST['user_id']);
        $totalSkor = 0;
        $jumlahSoal = count($dataSoal);
        $notAnswered = 0;
        $passAnswered = 0;
        $wrongAnswered = 0;
        $detailSoal = [];

        $pilgans = $pengerjaan->getPilgan();
        $alfaPilgan = [];
        foreach ($pilgans as $pil) {
            $getLast = explode('_', $pil['COLUMN_NAME']);
            $alfaPilgan[] = $getLast[1];
        }

        foreach ($dataSoal as $soal) {
            if (isset($_POST['soal_' . $soal['id_soal']])) {
                $jawaban = $_POST['soal_' . $soal['id_soal']];
                if ($jawaban == $soal['kunci_jawaban']) {
                    $passAnswered++;
                } else {
                    $wrongAnswered++;
                }
            } else {
                $notAnswered++;
            }

            $pilgan = [];
            foreach ($alfaPilgan as $pil) {
                $pilgan[$pil] = $soal['jawaban_' . $pil];
            }

            $detailSoal[] = [
                'soal' => $soal['soal'],
                'pilgan' => $pilgan,
                'jawab' => isset($_POST['soal_' . $soal['id_soal']]) ? $_POST['soal_' . $soal['id_soal']] : 'Belum dijawab',
                'kunci' => $soal['kunci_jawaban']
            ];
        }

        $dataInsert = [
            'end_time' => date('Y-m-d H:i:s'),
            'status_pengerjaan' => 1
        ];

        $where = [
            'user_id' => $user_id
        ];

        $res = $pengerjaan->updatePengerjaan('pengerjaan', $dataInsert, $where);

        // count skor
        $rumus = str_replace(['<jumlah_soal>', '<jumlah_benar>'], [$jumlahSoal, $passAnswered], $penilaian);
        eval('$totalSkor = ' . $rumus . ';');
        $totalSkor = round($totalSkor, 1);

        $dataInsertLaporan = [
            'email' => $_SESSION['user']['email'],
            'username' => $_SESSION['user']['username'],
            'nik' => $_SESSION['user']['nik'],
            'nama_lengkap' => $_SESSION['user']['nama_lengkap'],
            'jenis_kelamin' => $_SESSION['user']['jenis_kelamin'],
            'tempat_lahir' => $_SESSION['user']['tempat_lahir'],
            'tanggal_lahir' => $_SESSION['user']['tanggal_lahir'],
            'hasil' => json_encode([
                'jumlah_soal' => $jumlahSoal,
                'waktu_pengerjaan' => $dataPengerjaan['waktu'],
                'start_time' => $dataPengerjaan['start_time'],
                'end_time' => date('Y-m-d H:i:s'),
                'penilaian' => $penilaian,
                'not_answered' => $notAnswered,
                'pass_answered' => $passAnswered,
                'wrong_answered' => $wrongAnswered,
                'total_skor' => $totalSkor,
                'detail_soal' => $detailSoal,
            ])
        ];

        if ($res) {
            $saveLaporan = $pengerjaan->addPengerjaan('laporan', $dataInsertLaporan);
        } else {
            $saveLaporan = false;
        }

        if ($saveLaporan) {
            echo json_encode(['status' => 'success', 'message' => 'Pengerjaan berhasil disimpan', 'skor' => $totalSkor]);
        } else {
            echo json_encode(['status' => 'failed', 'message' => 'Pengerjaan gagal disimpan']);
        }
    }
}
