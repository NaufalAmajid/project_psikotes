<?php

date_default_timezone_set('Asia/Jakarta');

class Main
{
    private $conn;

    public function __construct()
    {
        $this->conn = DB::getInstance()->connection();
    }

    public function getAllData()
    {
        $queryLaporan = "SELECT COUNT(*) as total_laporan FROM laporan";
        $resultLaporan = $this->conn->query($queryLaporan);
        $resultLaporan->execute();
        $totalLaporan = $resultLaporan->fetch(PDO::FETCH_ASSOC);

        $queryUser = "SELECT 
                        COUNT(CASE WHEN role_id = 1 THEN 1 END) as total_admin,
                        COUNT(CASE WHEN role_id = 2 THEN 1 END) as total_user
                      FROM 
                        user
                      WHERE
                        is_active = 1";
        $resultUser = $this->conn->query($queryUser);
        $resultUser->execute();
        $totalUser = $resultUser->fetch(PDO::FETCH_ASSOC);

        $querySoal = "SELECT COUNT(*) as total_soal FROM soal";
        $resultSoal = $this->conn->query($querySoal);
        $resultSoal->execute();
        $totalSoal = $resultSoal->fetch(PDO::FETCH_ASSOC);

        return [
            'total_laporan' => $totalLaporan['total_laporan'],
            'total_admin' => $totalUser['total_admin'],
            'total_user' => $totalUser['total_user'],
            'total_soal' => $totalSoal['total_soal']
        ];
    }
}
