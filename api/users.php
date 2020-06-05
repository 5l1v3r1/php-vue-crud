<?php
// SET HEADER
header("Content-type: application/json");
include 'config.php';
include 'functions.php';


$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));
$warning = "";
$action = getAction();

if (checkOptions($action)) {
    switch ($action) {
        case 'read':
            $query = $db->prepare('SELECT * FROM users');
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
            break;
        case 'create':
            if (isDataEmpty($data)) {
                $user = json_decode(getRequestData($action, $data));
                $query = $db->prepare('INSERT INTO users(first_name,last_name,username,email) values(?,?,?,?)');
                $query->execute(array($user->first_name, $user->last_name, $user->username, $user->email));
                $error = $query->errorInfo();
                if (empty($error[2])) {
                    $warning = $user->first_name . ' ' . $user->last_name . ' kullanıcısı başarıyla eklendi';
                } else {
                    $warning = $user->first_name . ' ' . $user->last_name . ' kullanıcısını ekleme işlemi sırasında hata oluştu:' . $error[2];
                }
            }
            break;
        case 'update':
            if (isDataEmpty($data)) {
                $user = json_decode(getRequestData($action, $data));
                $query = $db->prepare("UPDATE users set first_name=?,last_name=?,username=? where email=?");
                $query->execute(array($user->first_name, $user->last_name, $user->username, $user->email));
                $error = $query->errorInfo();
                if (empty($error[2])) {
                    $warning = $user->email . ' email adresine ait kullanıcının bilgileri başarıyla güncellendi';
                } else {
                    $warning = $user->email . ' email adresine ait kullanıcının bilgilerini güncelleme işlemi esnasında hata oluştu:' . $error[2];
                }
            }
            break;
        case 'delete':
            if (isDataEmpty($data)) {
                $user = json_decode(getRequestData($action, $data));
                $query = $db->prepare("DELETE from users WHERE email=?");
                $query->execute(array($user->email));
                $error = $query->errorInfo();
                if (empty($error[2])) {
                    $warning = $user->email . ' email adresine ait kullanıcının bilgileri başarıyla silindi';
                } else {
                    $warning = $user->email . ' email adresine ait kullanıcının bilgilerini silme işlemi esnasında hata oluştu:' . $error[2];
                }
            }
            break;
        case 'search':
            if (isDataEmpty($data)) {
                $data = json_decode(getRequestData($action, $data));
                $query = $db->prepare("SELECT * FROM users WHERE first_name LIKE ? OR last_name LIKE ?");
                $query->execute(array('%' . $data->text . '%', '%' . $data->text . '%'));
                $data = $query->fetchAll(PDO::FETCH_OBJ);
                echo json_encode($data);
            }
            break;
        default:
            break;
    }
}
if ($warning) {
    echo $warning;
}
