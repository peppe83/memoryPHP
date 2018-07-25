<?php
class Users{
 
    // database connection and table name
    private $conn;
    private $table_name_users = "users";
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
    
    // getUser
    function getUser($username){
        $query = "SELECT
                u.id_user
            FROM
                " . $this->table_name_users . " u
            where u.username = '" . $username . "'";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    // getUserById
    function getUserById($userid){
    $query = "SELECT
                u.id_user
            FROM
                " . $this->table_name_users . " u
            where u.id_user = '" . $userid . "'";
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    
    // execute query
    $stmt->execute();
    
    return $stmt;
    }
    
    // readAll user
    function readAll($usernameAdmin){
    	$query = "SELECT
                u.id_user, u.username, u.password, u.name, u.surname, u.email, u.phone, u.description, u.enabled, u.date_creation, r.name as role
            FROM
                " . $this->table_name_users . " u, " . $this->table_name_roles . " r, " . $this->table_name_users_roles . " ur
            where ur.id_user = u.id_user and r.id_role = ur.id_role 
            and (SELECT u.id_user FROM " . $this->table_name_users . " u, " . $this->table_name_users_roles . " ur WHERE ur.id_user = u.id_user and 'ROLE_ADMIN' = ur.id_role and u.id_user='" . $usernameAdmin . "')
            ORDER BY
               	u.surname DESC";
    
    	// prepare query statement
    	$stmt = $this->conn->prepare($query);
    
    	// execute query
    	$stmt->execute();
    
    	return $stmt;
    }
    
    // loginUser
    function loginUser($userURL, $passwordURL){
        $query = "SELECT
                u.id_user, u.username, u.password, u.name, u.surname, u.email, u.phone, u.description, u.enabled, u.date_creation, r.name as role
            FROM
                " . $this->table_name_users . " u, " . $this->table_name_roles . " r, " . $this->table_name_users_roles . " ur
            where ur.id_user = u.id_user and r.id_role = ur.id_role and u.username = '" . $userURL . "' and u.password = '" . $passwordURL . "'
            ORDER BY
               	u.surname DESC";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    // buildUser
    function buildUser($username, $password, $name, $surname, $email, $phone, $description, $role){    
        $query = "INSERT INTO " . $this->table_name_users . " (username, password, name, surname, email, phone, description, enabled)
            VALUES ('" . $username . "', '" . $password . "', '" . $name . "', '" . $surname . "', '" . $email . "', '" . $phone . "', '" . $description . "', false)";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
    return $stmt;
    }
    
    // modifyUser
    
    function modifyUser($iduser, $enabled, $email, $phone, $role) {
        //$query = "INSERT INTO " . $this->table_name_users . " (username, password, name, surname, email, phone, description, enabled)
        //    VALUES ('" . $username . "', '" . $password . "', '" . $name . "', '" . $surname . "', '" . $email . "', '" . $phone . "', '" . $description . "', false)";
        
        $update = "";
        
        if ($iduser==null || $iduser=="") {
            
            return;
        }
        
        if ($enabled!=null && $enabled!="") {
            $update = "enabled = " . $enabled;
        }
        
        if ($email!=null && $email!="") {
            if ($update=="") {
                $update = "email = " . $email;
            } else {
                $update = $update . " and email = '" . $email . "'";
            }
        }
        
        if ($phone!=null && $phone!="") {
            if ($update=="") {
                $update = "phone = " . $phone;
            } else {
                $update = $update . " and phone = '" . $phone . "'";
            }
        }
        
//         if ($role!=null && $role!="") {
//             if ($update=="") {
//                 $update = "role = " . $role;
//             } else {
//                 $update = $update . " and role = " . $role;
//             }
//         }
        
        
        if ($update=="") {
            
            return;
        }
        
        $query = "UPDATE " . $this->table_name_users . " SET " . $update . " WHERE id_user = " . $iduser;
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
}