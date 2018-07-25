<?php
class Database{

	// specify your own database credentials
// 	private $host = "localhost";
// 	private $db_name = "memory";
// 	private $username = "root";
// 	private $password = "terraria";
	
// 	private $host = "89.46.111.69";
// 	private $db_name = "Sql1234652_1";
// 	private $username = "Sql1234652";
// 	private $password = "42rk613re6";

	private $host = "127.0.0.1";
	private $db_name = "memory";
	private $username = "memory";
	private $password = "memory";
	public $conn;

	// get the database connection
	public function getConnection(){

		$this->conn = null;
		
		try{
			$this->conn = new PDO("mysql:host=" . $this->host . ";port=3306;dbname=" . $this->db_name, $this->username, $this->password);
			$this->conn->exec("set names utf8");
		}catch(PDOException $exception){
			//echo "Connection error: " . $exception->getMessage();
			//print_r( $exception );
		   
		}

		return $this->conn;
	}
}
?>