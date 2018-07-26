<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$idFileURL =  $_GET['idfile'];
$idUserURL = $_GET['iduser'];
$usernameURL =  $_GET['username'];
$passwordURL = $_GET['password'];
$keysearchURL = $_GET['keysearch'];
$typeAccountURL =  $_GET['typeAccount'];
$linkURL =  $_GET['link'];
$optURL =  $_GET['opt'];
    
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
$stmt = $account->addAccount($idFileURL, $idUserURL, $usernameURL, $passwordURL, $keysearchURL, $typeAccountURL, $linkURL, $optURL);
if($stmt==null){
    //     echo json_encode(
    //         array("error" => "Impossibile interrogare il database. Riprovare")
    //     );
    return;
}

$num = $stmt->rowCount();

if($num>0){
    echo json_encode(
        array("insert" => "Inserimento avvenuto correttamente")
        );
} else {
	echo json_encode(
			array("message" => "Impossibile aggiungere l'account")
			);
}
?>