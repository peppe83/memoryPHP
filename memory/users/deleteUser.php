<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/users.php';

$iduser =  $_GET['iduser'];



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
$stmt = $users->deleteUser($iduser);
if($stmt==null){
//     echo json_encode(
//         array("error" => "Impossibile interrogare il database. Riprovare")
//     );
    return;
}

$num = $stmt->rowCount();
if($num>0){
    echo json_encode(
        array("error" => "Utente eliminato")
    );    
} else {
    echo json_encode(
        array("message" => "Impossibile eliminare l'utente. Riprovare.")
    );
}
?>