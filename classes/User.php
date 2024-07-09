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

    public function getAllUser($where = null)
    {
        $where = $where ? "WHERE $where" : '';
        $query = "SELECT * FROM user JOIN role ON user.role_id = role.id_role $where";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id_user)
    {
        $query = "SELECT * FROM user WHERE id_user = $id_user";
        $result = $this->conn->query($query);
        $result->execute();

        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function checkEmailUsername($username, $email)
    {
        $query = "SELECT * FROM user WHERE username = '$username' OR email = '$email'";
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
                            'icon' => 'bx bx-error'
                        ]);
                        exit;
                    }
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Terjadi kesalahan saat mengupload file',
                        'icon' => 'bx bx-error'
                    ]);
                    exit;
                }
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Ekstensi file tidak diizinkan',
                    'icon' => 'bx bx-error'
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
                    'icon' => 'bx bx-info-circle'
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
                'icon' => 'bx bx-check'
            ];
        } else {
            $res = [
                'status' => 'error',
                'message' => 'Data gagal diubah',
                'icon' => 'bx bx-error'
            ];
        }

        echo json_encode($res);
    }
}
