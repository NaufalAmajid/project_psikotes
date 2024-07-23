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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    session_start();
    require_once '../config/connection.php';
    require_once '../classes/DB.php';

    $setting = new Setting();

    if ($_POST['action'] == 'updateSetting') {
        $data = $_POST;
        unset($data['action']);
        $where = ['id' => 1];
        $res = $setting->updateSetting('setting', $data, $where);
        if ($res) {
            echo json_encode(['status' => 'success', 'message' => 'Setting berhasil diupdate']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Setting gagal diupdate']);
        }
    }
}
