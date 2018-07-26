<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$usernameURL =  $_GET['username'];
$usernameParentURL = $_GET['usernameParent'];
$keysearchURL = $_GET['keysearch'];
$typeAccountURL =  $_GET['typeAccount'];

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
$stmt = $account->readAll($usernameURL, $usernameParentURL, $keysearchURL, $typeAccountURL);
if($stmt==null){
    //     echo json_encode(
    //         array("error" => "Impossibile interrogare il database. Riprovare")
    //     );
    return;
}

$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	// products array
	$products_arr=array();
	$products_arr["records"]=array();

	// retrieve our table contents
	// fetch() is faster than fetchAll()
	// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// extract row
		// this will make $row['name'] to
		// just $name only
		extract($row);

		$product_item=array(
		    "id_file" => $id_file,
		    "id_db" => $id_db,
		    "id_user" => $id_user,
		    "id_type" => $id_type,
		    "username" => $username,
		    "password" => $password,
		    "opt" => $opt,
		    "keysearch" => $keysearch,
		    "delete" => $del
		);

		array_push($products_arr["records"], $product_item);
	}

	echo json_encode($products_arr);
}

else{
	echo json_encode(
		array("message" => "Nessun account trovato")
	);
}
?>