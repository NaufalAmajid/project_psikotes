<?php

date_default_timezone_set('Asia/Jakarta');

class Laporan
{
    private $conn;

    public function __construct()
    {
        $this->conn = DB::getInstance()->connection();
    }

    public function getLaporan()
    {
        $query = "SELECT * FROM laporan";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
