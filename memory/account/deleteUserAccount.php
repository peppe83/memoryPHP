<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$iddb =  $_GET['iddb'];
    
// include database and object files
include_once '../config/database.php';
include_once '../objects/account.php';

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
$account = new Account($db);

// query products
$stmt = $account->removeAccount($iddb);
if($stmt==null){
    //     echo json_encode(
    //         array("error" => "Impossibile interrogare il database. Riprovare")
    //     );
    return;
}

$num = $stmt->rowCount();

if($num==1){
    echo json_encode(
        array("delete" => "Account eliminato correttamente")
        );
} else {
	echo json_encode(
	   array("message" => "Impossibile eliminare l'account.")
	);
}
?>