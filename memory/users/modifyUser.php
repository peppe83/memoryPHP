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

// initialize object
$users = new Users($db);

// query products
$stmt = $users->getUserById($iduser);
$num = $stmt->rowCount();
if($num==0){
    echo json_encode(
        array("error" => "Utente da modificare non trovato. Riprovare")
        );
    return;
}

// query products
$stmt = $users->modifyUser($iduser, $enabled, $email, $phone, $role);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){
    echo json_encode(
        array("insert" => "ok", "msg" => "Modifica avvenuta correttamente")
        );
	
	
	/*// retrieve our table contents
	// fetch() is faster than fetchAll()
	// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// extract row
		// this will make $row['name'] to
		// just $name only
		extract($row);

		$product_item=array(
				"id_user" => $id_user,
				"username" => $username,
				"password" => $password,
				"name" => $name,
				"surname" => $surname,
				"email" => $email,
				"phone" => $phone,
				"enabled" => $enabled,
				"description" => html_entity_decode($description),
				"date_creation" => $date_creation,
		        "role" => $role
		);

		array_push($products_arr["records"], $product_item);
	}*/

	//echo json_encode($products_arr);
}

else{
	echo json_encode(
			array("message" => "Nessuna modifica effettuata.")
			);
}
?>