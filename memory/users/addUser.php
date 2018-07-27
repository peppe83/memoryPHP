<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/users.php';

$username =  $_GET['username'];
$password =  $_GET['password'];
$name =  $_GET['name'];
$surname =  $_GET['surname'];
$email =  $_GET['email'];
$phone =  $_GET['phone'];
$description =  $_GET['description'];
$role =  $_GET['role'];

//http://localhost/dev_memory_php/memory/users/addUser.php?username=few&password=few&name=NAME&surname=sur&email=mail&phone=ph&description=des&role=ROLE_USER

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
if($db==null){
    echo json_encode(
        array("error" => "Impossibile accedere al DB. Riprovare")
        );
    return;
}
// initialize object
$users = new Users($db);

// query products
$stmt = $users->getIdUserByUsername($username);
if($stmt==null){
//     echo json_encode(
//         array("error" => "Impossibile interrogare il database. Riprovare")
//     );
    return;
}

$num = $stmt->rowCount();
if($num>0){
    echo json_encode(
        array("error" => "Username esistente. Riprovare")
        );
    return;
}

// query products
$stmt = $users->addUser($username, $password, $name, $surname, $email, $phone, $description);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){
    //inserisco il ruolo
    $stmt = $users->getIdUserByUsername($username);
    $num = $stmt->rowCount();
    if($num>0){
        $result = $stmt->fetch();
        $id_user = $result[0];
        $stmt = $users->addUserRole($id_user, $role);
        $num = $stmt->rowCount();
        if($num>0){
            $stmt = $users->updateDateUpdate($id_user);
            $num = $stmt->rowCount();
            if($num>0){
                echo json_encode(
                    array("insert" => "ok", "msg" => "Inserimento avvenuto correttamente")
                );
                return;
            }
        }
    }
} 

echo json_encode(
    array("message" => "Impossibile creare il nuovo utente.")
);

?>