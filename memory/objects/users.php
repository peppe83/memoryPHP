<?php
class Users{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
    private $table_name_roles = "roles";
    private $table_name_users_roles = "users_roles";
 
    // object properties
    public $id_user;
    public $username;
    public $password;
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $description;
    public $enabled;
    public $date_creation;
    public $role;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read products
    function readAll($usernameAdmin){
    	$query = "SELECT
                u.id_user, u.username, u.password, u.name, u.surname, u.email, u.phone, u.description, u.enabled, u.date_creation, r.name as role
            FROM
                " . $this->table_name . " u, " . $this->table_name_roles . " r, " . $this->table_name_users_roles . " ur

            where ur.id_user = u.id_user and r.id_role = ur.id_role 
            and (SELECT u.id_user FROM " . $this->table_name . " u, " . $this->table_name_users_roles . " ur WHERE ur.id_user = u.id_user and 'ROLE_ADMIN' = ur.id_role and u.id_user='" . $usernameAdmin . "')
            ORDER BY
               	u.surname DESC";
    
    	// prepare query statement
    	$stmt = $this->conn->prepare($query);
    
    	// execute query
    	$stmt->execute();
    
    	return $stmt;
    }
    
    // read products
    function loginUser($userURL, $passwordURL){
        $query = "SELECT
                u.id_user, u.username, u.password, u.name, u.surname, u.email, u.phone, u.description, u.enabled, u.date_creation, r.name as role
            FROM
                " . $this->table_name . " u, " . $this->table_name_roles . " r, " . $this->table_name_users_roles . " ur
                    
            where ur.id_user = u.id_user and r.id_role = ur.id_role and u.username = '" . $userURL . "' and u.password = '" . $passwordURL . "'
            ORDER BY
               	u.surname DESC";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
}