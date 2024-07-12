<?php

class Setting
{
    private $conn;

    public function __construct()
    {
        $this->conn = DB::getInstance()->connection();
    }

    public function updateSetting($table, $data, $where)
    {
        $db = DB::getInstance();
        $res = $db->update($table, $data, $where);
        return $res;
    }

    public function getSetting()
    {
        $query = "SELECT * FROM setting";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
