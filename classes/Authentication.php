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
        $query = "select
                        date_format(pjdl.tanggal, '%Y-%m-%d') as tanggal_jadwal,
                        role.*,
	                    user.*
                    from
                        user
                    join role on
                        user.role_id = role.id_role
                    left join penjadwalan pjdl on
                        user.id_user = pjdl.peserta
                        $where";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    session_start();
    require_once '../config/connection.php';
    require_once '../config/functions.php';
    require_once '../classes/DB.php';

    $auth = new Authentication();
    $funt = new Functions();

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

        $checkUser = $auth->getAllUser("email = '$emailusername' OR username = '$emailusername' AND is_active = 1");
        if (count($checkUser) > 0) {

            // check role dan jadwal
            if ($checkUser[0]['role_id'] == 2) {
                if (is_null($checkUser[0]['tanggal_jadwal'])) {
                    $res = [
                        'status' => 'error',
                        'message' => 'Anda Belum Dijadwalkan!',
                        'timer'   => 2000
                    ];
                    echo json_encode($res);
                    exit;
                } else if ($checkUser[0]['tanggal_jadwal'] != date('Y-m-d')) {
                    $res = [
                        'status' => 'error',
                        'message' => 'Jadwal Anda Pada Tanggal ' . $funt->dateIndonesia($checkUser[0]['tanggal_jadwal']) . '!',
                        'timer'   => 3000
                    ];
                    echo json_encode($res);
                    exit;
                }
            }

            if ($checkUser[0]['password'] == $password) {
                $_SESSION['user'] = $checkUser[0];
                $_SESSION['is_login'] = true;
                $res = [
                    'status' => 'success',
                    'message' => 'Login Berhasil!',
                    'timer'   => 1500
                ];
            } else {
                $res = [
                    'status' => 'error',
                    'message' => 'Password Salah!',
                    'timer'   => 1500
                ];
            }
        } else {
            $res = [
                'status' => 'error',
                'message' => 'Username atau Email tidak ditemukan!',
                'timer'   => 1500
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
