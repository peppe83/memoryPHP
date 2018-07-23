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
$deleteURL =  $_GET['delete'];
    
// include database and object files
include_once '../config/database.php';
include_once '../objects/account.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$account = new Account($db);

// query products
$stmt = $account->addAccount($idFileURL, $idUserURL, $usernameURL, $passwordURL, $keysearchURL, $typeAccountURL, $linkURL, $optURL, $deleteURL);
$num = $stmt->rowCount();

// check if more than 0 record found
// if($num>0){

// 	// products array
// 	$products_arr=array();
// 	$products_arr["records"]=array();

// 	// retrieve our table contents
// 	// fetch() is faster than fetchAll()
// 	// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
// 	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
// 		// extract row
// 		// this will make $row['name'] to
// 		// just $name only
// 		extract($row);

// 		$product_item=array(
// 		    "id_file" => $id_file,
// 		    "id_db" => $id_db,
// 		    "id_user" => $id_user,
// 		    "id_type" => $id_type,
// 		    "username" => $username,
// 		    "password" => $password,
// 		    "opt" => $opt,
// 		    "keysearch" => $keysearch,
// 		    "delete" => $del
// 		);

// 		array_push($products_arr["records"], $product_item);
// 	}

// 	echo json_encode($products_arr);
// }
if($num>0){
    echo json_encode(
        array("insert" => "ok")
        );
} else {
	echo json_encode(
			array("message" => "No account found.")
			);
}
?>