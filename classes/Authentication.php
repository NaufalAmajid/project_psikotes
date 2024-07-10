<?php

class Authentication
{
    private $conn;

    public function __construct()
    {
        $this->conn = DB::getInstance()->connection();
    }

    public function register($table, $data)
    {
        $db = DB::getInstance();
        $res = $db->add($table, $data);
        return $res;
    }

    public function getAllUser($where = null)
    {
        $where = $where ? "WHERE $where" : '';
        $query = "SELECT * FROM user JOIN role ON user.role_id = role.id_role $where";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    session_start();
    require_once '../config/connection.php';
    require_once '../classes/DB.php';

    $auth = new Authentication();

    if ($_POST['action'] == 'register') {
        $username       = $_POST['username'];
        $email          = $_POST['email'];
        $nama_lengkap   = $_POST['nama_lengkap'];
        $password       = md5($_POST['password']);

        $checkUser = $auth->getAllUser("username = '$username' OR email = '$email' AND role_id = 2 AND is_active = 1");
        if (count($checkUser) > 0) {
            $res = [
                'status' => 'error',
                'message' => 'Username atau Email sudah ada!'
            ];
        } else {
            $data = [
                'username'      => $username,
                'email'         => $email,
                'nama_lengkap'  => $nama_lengkap,
                'password'      => $password,
                'role_id'       => 2,
            ];

            $res = $auth->register('user', $data);
            if ($res) {
                $res = [
                    'status' => 'success',
                    'message' => 'Registrasi Berhasil!'
                ];
            } else {
                $res = [
                    'status' => 'error',
                    'message' => 'Registrasi Gagal!'
                ];
            }
        }

        echo json_encode($res);
    }

    if ($_POST['action'] == 'login') {
        $emailusername = $_POST['emailusername'];
        $password = md5($_POST['password']);

        $checkUser = $auth->getAllUser("email = '$emailusername' OR username = '$emailusername' AND role_id = 2 AND is_active = 1");
        if (count($checkUser) > 0) {
            if ($checkUser[0]['password'] == $password) {
                $_SESSION['user'] = $checkUser[0];
                $_SESSION['is_login'] = true;
                $res = [
                    'status' => 'success',
                    'message' => 'Login Berhasil!'
                ];
            } else {
                $res = [
                    'status' => 'error',
                    'message' => 'Password Salah!'
                ];
            }
        } else {
            $res = [
                'status' => 'error',
                'message' => 'Username atau Email tidak ditemukan!'
            ];
        }

        echo json_encode($res);
    }

    if ($_POST['action'] == 'logout') {
        $destroy = session_destroy();
        if ($destroy) {
            $res = [
                'status' => 'success',
                'message' => 'Logout Berhasil!'
            ];
        } else {
            $res = [
                'status' => 'error',
                'message' => 'Logout Gagal!'
            ];
        }

        echo json_encode($res);
    }
}
