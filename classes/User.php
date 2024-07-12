<?php

class User
{
    private $conn;

    public function __construct()
    {
        $this->conn = DB::getInstance()->connection();
    }

    public function updateUser($table, $data, $where)
    {
        $db = DB::getInstance();
        $res = $db->update($table, $data, $where);
        return $res;
    }

    public function insertUser($table, $data)
    {
        $db = DB::getInstance();
        $res = $db->insert($table, $data);
        return $res;
    }

    public function getAllUser($where = null)
    {
        $where = $where ?: '';
        $query = "SELECT * FROM user JOIN role ON user.role_id = role.id_role WHERE is_active = 1 $where";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRole()
    {
        $query = "SELECT * FROM role";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id_user, $where = null)
    {
        $where = $where ?: '';
        $query = "SELECT * FROM user JOIN role ON user.role_id = role.id_role WHERE id_user = $id_user $where";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function checkEmailUsername($username, $email)
    {
        $query = "SELECT * FROM user WHERE (username = '$username' OR email = '$email') AND is_active = 1";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetch(PDO::FETCH_ASSOC);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    session_start();
    require_once '../config/connection.php';
    require_once '../classes/DB.php';

    $user = new User();

    if ($_POST['action'] == 'saveUser') {
        $checkUser = $user->checkEmailUsername($_POST['username'], $_POST['email']);
        if ($checkUser) {
            if ($checkUser['id_user'] != $_POST['id_user']) {
                echo json_encode([
                    'status' => 'info',
                    'message' => 'username / email sudah digunakan!',
                ]);
                exit;
            }
        }

        $insertUser = $_POST;
        unset($insertUser['action'], $insertUser['id_user'], $insertUser['statusForm']);
        if (isset($_POST['password'])) {
            $insertUser['password'] = md5($insertUser['password']);
        }

        if ($_POST['statusForm'] == 'add') {
            $saveUser = $user->insertUser('user', $insertUser);
        } else {
            $where = [
                'id_user' => $_POST['id_user']
            ];
            $saveUser = $user->updateUser('user', $insertUser, $where);
        }

        if ($saveUser) {
            $res = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
            ];
        } else {
            $res = [
                'status' => 'error',
                'message' => 'Data gagal disimpan',
            ];
        }

        echo json_encode($res);
    }

    if ($_POST['action'] == 'deleteUser') {
        $editUser = [
            'is_active' => 0
        ];
        $where = [
            'id_user' => $_POST['id_user']
        ];
        $updateUser = $user->updateUser('user', $editUser, $where);

        if ($updateUser) {
            $res = [
                'status' => 'success',
                'message' => 'Data berhasil dihapus!',
            ];
        } else {
            $res = [
                'status' => 'error',
                'message' => 'Data gagal dihapus!',
            ];
        }

        echo json_encode($res);
    }

    if ($_POST['action'] == 'updateAccountUser') {
        // check photo
        if (isset($_FILES['photo'])) {
            $photo               = $_FILES['photo'];
            $photo_name          = $photo['name'];
            $photo_tmp           = $photo['tmp_name'];
            $photo_size          = $photo['size'];
            $photo_error         = $photo['error'];

            $photo_ext           = explode('.', $photo_name);
            $photo_actual_ext    = strtolower(end($photo_ext));

            $allowed             = ['jpg', 'jpeg', 'png'];

            if (in_array($photo_actual_ext, $allowed)) {
                if ($photo_error === 0) {
                    if ($photo_size < 1000000) {
                        $nama_user = strtolower(preg_replace('/\s+/', '', $_SESSION['user']['nama_lengkap']));
                        $nama_user = preg_replace('/[^\w\s]|_/', '', $nama_user);
                        $photo_new_name = $nama_user . '_' . $_SESSION['user']['id_user'] . '.' . $photo_actual_ext;
                        $photo_destination = '../myfiles/photo/' . $photo_new_name;
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Ukuran file terlalu besar',
                        ]);
                        exit;
                    }
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Terjadi kesalahan saat mengupload file',
                    ]);
                    exit;
                }
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Ekstensi file tidak diizinkan',
                ]);
                exit;
            }
        }
        // end check photo

        // check username and email
        $checkUser = $user->checkEmailUsername($_POST['username'], $_POST['email']);
        if ($checkUser) {
            if ($checkUser['id_user'] != $_SESSION['user']['id_user']) {
                echo json_encode([
                    'status' => 'info',
                    'message' => 'username / email sudah digunakan!',
                ]);
                exit;
            }
        }
        // end check username and email

        $insertUser = $_POST;
        foreach ($insertUser as $key => $value) {
            if ($value == '') {
                unset($insertUser[$key]);
            }
        }
        unset($insertUser['action'], $insertUser['id_user']);

        //check change password
        if ($_POST['password'] != '') {
            $insertUser['password'] = md5($_POST['password']);
        }
        // end check change password

        // save photo
        if (isset($photo_new_name)) {
            move_uploaded_file($photo_tmp, $photo_destination);
            $insertUser['photo_profile'] = $photo_new_name;
        }
        // end save photo

        $updateUser = $user->updateUser('user', $insertUser, ['id_user' => $_SESSION['user']['id_user']]);

        if ($updateUser) {
            $_SESSION['user'] = $user->getUserById($_SESSION['user']['id_user']);

            $res = [
                'status' => 'success',
                'message' => 'Data berhasil diubah',
            ];

            if ($_POST['password'] != '') {
                session_destroy();
            }
        } else {
            $res = [
                'status' => 'error',
                'message' => 'Data gagal diubah',
            ];
        }

        echo json_encode($res);
    }

    if ($_POST['action'] == 'deleteAccount') {
        $editUser = [
            'is_active' => 0
        ];
        $where = [
            'id_user' => $_POST['id_user']
        ];
        $updateUser = $user->updateUser('user', $editUser, $where);

        if ($updateUser) {
            session_destroy();
            $res = [
                'status' => 'success',
                'message' => 'Akun anda berhasil dihapus!',
            ];
        } else {
            $res = [
                'status' => 'success',
                'message' => 'Akun anda berhasil dihapus!',
            ];
        }

        echo json_encode($res);
    }

    if ($_POST['action'] == 'checkPassword') {
        $password = md5($_POST['real_password']);
        $checkPassword = $user->getUserById($_POST['id_user'], "AND password = '$password'");
        if ($checkPassword) {
            $res = [
                'status' => 'success'
            ];
        } else {
            $res = [
                'status' => 'error'
            ];
        }

        echo json_encode($res);
    }
}
