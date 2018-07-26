<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/users.php';

$iduser =  $_GET['iduser'];
$enabled =  $_GET['enabled'];
$email =  $_GET['email'];
$phone =  $_GET['phone'];
$role =  $_GET['role'];

//http://localhost/dev_memory_php/memory/users/modifyUser.php?iduser=1&enabled&email=mail&phone=ph&role=ROLE_USER

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
$stmt = $users->getUserById($iduser);
if($stmt==null){
//     echo json_encode(
//         array("error" => "Impossibile interrogare il database. Riprovare")
//     );
    return;
}

$num = $stmt->rowCount();
if($num==0){
    echo json_encode(
        array("error" => "Utente da modificare non trovato. Riprovare")
    );
    return;
}

// query products
$stmt = $users->modifyUser($iduser, $enabled, $email, $phone);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){
    $stmt = $users->modifyUserRole($iduser, $role);
    $num = $stmt->rowCount();
    if($num>0){
        echo json_encode(
            array("insert" => "ok", "msg" => "Modifica avvenuta correttamente")
        );
    } else {
        echo json_encode(
            array("message" => "Nessuna modifica effettuata.")
            );
    }
} else {
	echo json_encode(
	   array("message" => "Nessuna modifica effettuata.")
	);
}
?>