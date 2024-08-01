<?php

date_default_timezone_set('Asia/Jakarta');

class Penjadwalan
{
    private $conn;

    public function __construct()
    {
        $this->conn = DB::getInstance()->connection();
    }

    public function updatePenjadwalan($table, $data, $where)
    {
        $db = DB::getInstance();
        $res = $db->update($table, $data, $where);
        return $res;
    }

    public function addPenjadwalan($table, $data)
    {
        $db = DB::getInstance();
        $res = $db->add($table, $data);
        return $res;
    }

    public function deletePenjadwalan($table, $where)
    {
        $db = DB::getInstance();
        $res = $db->delete($table, $where);
        return $res;
    }

    public function getPenjadwalan()
    {
        $query = "select
                        pjd.no_jadwal,
                        date_format(pjd.tanggal, '%Y-%m-%d') as hari,
                        date_format(pjd.tanggal, '%H:%i') as jam,
                        usr.nama_lengkap, 
                        usr.photo_profile
                    from
                        penjadwalan pjd
                    left join user usr on
                        pjd.peserta = usr.id_user";
        $result = $this->conn->query($query);
        $result->execute();

        $byNoJadwal = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $byNoJadwal[$row['no_jadwal']]['hari'] = $row['hari'];
            $byNoJadwal[$row['no_jadwal']]['jam'] = $row['jam'];
            $byNoJadwal[$row['no_jadwal']]['peserta'][] = [
                'nama_lengkap' => $row['nama_lengkap'],
                'photo_profile' => $row['photo_profile']
            ];
        }

        return $byNoJadwal;
    }

    public function getJadwalByPeserta($peserta)
    {
        $query = "select
                    *
                from
                    penjadwalan pjd
                left join user usr on
                    pjd.peserta = usr.id_user
                where
                    pjd.peserta = $peserta";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function getJadwalByNoJadwal($no_jadwal)
    {
        $query = "select
                    *
                from
                    penjadwalan pjd
                left join user usr on
                    pjd.peserta = usr.id_user
                where
                    pjd.no_jadwal = '$no_jadwal'";
        $result = $this->conn->query($query);
        $result->execute();

        $byNoJadwal = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $byNoJadwal['tanggal'] = $row['tanggal'];
            $byNoJadwal['id_peserta'][] = $row['peserta'];
        }

        return $byNoJadwal;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    require_once '../config/connection.php';
    require_once '../classes/DB.php';

    $penjadwalan = new Penjadwalan();

    if ($_POST['action'] == 'savePenjadwalan') {
        $listPeserta = $_POST['peserta'];
        $no_jadwal = date('YmdHis') . rand(100, 999);
        $no_jadwal = $_POST['no_jadwal'] ?: $no_jadwal;
        $output = [];
        foreach ($listPeserta as $peserta) {
            $checkJadwal = $penjadwalan->getJadwalByPeserta($peserta);
            if ($checkJadwal) {
                $output[] = ['status' => 'failed', 'message' => $checkJadwal['nama_lengkap'] . ' sudah terjadwal'];
            } else {
                $dataInsert = [
                    'no_jadwal' => $no_jadwal,
                    'tanggal' => $_POST['tanggal'],
                    'peserta' => $peserta
                ];
                $res = $penjadwalan->addPenjadwalan('penjadwalan', $dataInsert);
                if ($res) {
                    $output[] = ['status' => 'success', 'message' => 'Berhasil'];
                } else {
                    $output[] = ['status' => 'failed'];
                }
            }
        }

        // count success
        $success = 0;
        $failed = 0;
        $msgFailed = '';
        foreach ($output as $item) {
            if ($item['status'] == 'success') {
                $success++;
            } else {
                $failed++;
                if (isset($item['message'])) {
                    $msgFailed .= $item['message'] . '<br>';
                }
            }
        }

        $lastOutput = "Data berhasil disimpan: $success <br> Data gagal disimpan: $failed <br> $msgFailed";

        echo $lastOutput;
    }

    if ($_POST['action'] == 'editPenjadwalan') {
        $getJadwalByNoJadwal = $penjadwalan->getJadwalByNoJadwal($_POST['no_jadwal']);
        $listIdPeserta = $getJadwalByNoJadwal['id_peserta'];
        $listPeserta = isset($_POST['peserta']) ? $_POST['peserta'] : [];

        // update
        foreach ($listIdPeserta as $idPeserta) {
            if (!in_array($idPeserta, $listPeserta) || count($listPeserta) == 0) {
                $where = ['no_jadwal' => $_POST['no_jadwal'], 'peserta' => $idPeserta];
                $res = $penjadwalan->deletePenjadwalan('penjadwalan', $where);
            } else {
                $where = ['no_jadwal' => $_POST['no_jadwal']];
                $data = ['tanggal' => $_POST['tanggal']];
                $res = $penjadwalan->updatePenjadwalan('penjadwalan', $data, $where);
            }
        }

        // insert
        foreach ($listPeserta as $peserta) {
            if (!in_array($peserta, $listIdPeserta) || count($listIdPeserta) == 0) {
                $dataInsert = [
                    'no_jadwal' => $_POST['no_jadwal'],
                    'tanggal' => $_POST['tanggal'],
                    'peserta' => $peserta
                ];
                $res = $penjadwalan->addPenjadwalan('penjadwalan', $dataInsert);
            }
        }

        $msg = "Data berhasil diubah";

        echo $msg;
    }

    if ($_POST['action'] == 'deleteJadwal') {
        $where = ['no_jadwal' => $_POST['no_jadwal']];
        $res = $penjadwalan->deletePenjadwalan('penjadwalan', $where);
        if ($res) {
            $msg = ['status' => 'success', 'message' => 'Data berhasil dihapus'];
        } else {
            $msg = ['status' => 'error', 'message' => 'Data gagal dihapus'];
        }

        echo json_encode($msg);
    }
}
