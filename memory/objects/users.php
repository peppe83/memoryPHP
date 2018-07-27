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
    public $date_update;
    public $role;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // getIdUserByUsername
    function getIdUserByUsername($username){
        if ($username==null || $username=="") {
            echo json_encode(
                array("error" => "Utente non riconosciuto. Riprovare")
                );
            return;
        }
        $query = "SELECT u.id_user FROM " . $this->table_name_users . " u where u.username = '" . $username . "'";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    // getUserById
    function getUserById($userid){
        if ($userid==null || $userid=="") {
            echo json_encode(
                array("error" => "Utente non riconosciuto. Riprovare")
            );
            return;
        }
        
        $query = "SELECT u.id_user
                FROM " . $this->table_name_users . " u
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
                u.id_user, u.username, u.password, u.name, u.surname, u.email, u.phone, u.description, u.enabled, u.date_creation, u.date_update, r.name as role
            FROM
                " . $this->table_name_users . " u, " . $this->table_name_roles . " r, " . $this->table_name_users_roles . " ur
            where ur.id_user = u.id_user and r.id_role = ur.id_role 
            and (SELECT u.id_user FROM " . $this->table_name_users . " u, " . $this->table_name_users_roles . " ur WHERE ur.id_user = u.id_user and 
                    'ROLE_ADMIN' = ur.id_role and u.id_user='" . $usernameAdmin . "')
            ORDER BY u.surname DESC";
    
    	// prepare query statement
    	$stmt = $this->conn->prepare($query);
    
    	// execute query
    	$stmt->execute();
    
    	return $stmt;
    }
    
    // loginUser
    function loginUser($userURL, $passwordURL){
        $query = "SELECT
                u.enabled, u.id_user, u.username, u.password, u.name, u.surname, u.email, u.phone, u.description, u.date_creation, u.date_update, r.name as role
            FROM
                " . $this->table_name_users . " u, " . $this->table_name_roles . " r, " . $this->table_name_users_roles . " ur
            where ur.id_user = u.id_user and r.id_role = ur.id_role and u.username = '" . $userURL . "' and u.password = '" . $passwordURL . "'
            ORDER BY u.surname DESC";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    // addUser
    function addUser($username, $password, $name, $surname, $email, $phone, $description){    
        $query = "INSERT INTO " . $this->table_name_users . " (username, password, name, surname, email, phone, description, enabled)
            VALUES ('" . $username . "', '" . $password . "', '" . $name . "', '" . $surname . "', '" . $email . "', '" . $phone . "', '" . $description . "', false)";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    
    // addUserRole
    function addUserRole($iduser, $role) {
        if ($iduser==null || $iduser=="") {
            echo json_encode(
                array("error" => "Utente da modificare non riconosciuto. Riprovare")
                );
            return;
        }
        
        if ($role==null || $role=="") {
            return;
        }
        
        if ($role != "ROLE_ADMIN") {
            $role = "ROLE_USER";
        }
        
        $query = "INSERT INTO  " . $this->table_name_users_roles . " (id_user, id_role) VALUES ('" . $iduser . "','" . $role . "')";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    // modifyUser
    function modifyUser($iduser, $enabled, $email, $phone) {
        if ($iduser==null || $iduser=="") {
            echo json_encode(
                array("error" => "Utente da modificare non riconosciuto. Riprovare")
            );
            return;
        }
        
        $update = "date_update = now()";
        if ($enabled!=null && $enabled!="") {
            $update = $update . ", enabled = " . $enabled;
        }
        
        if ($email!=null && $email!="") {
            $update = $update . ", email = '" . $email . "'";
        }
        
        if ($phone!=null && $phone!="") {
            $update = $update . ", phone = '" . $phone . "'";
        }
        
        $query = "UPDATE " . $this->table_name_users . " SET " . $update . " WHERE id_user = " . $iduser;
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    // updateDateUpdate
    function updateDateUpdate($iduser) {
        if ($iduser==null || $iduser=="") {
            echo json_encode(
                array("error" => "Utente da modificare non riconosciuto. Riprovare")
                );
            return;
        }
        
        $query = "UPDATE " . $this->table_name_users . " SET date_update = now() WHERE id_user = " . $iduser;
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    // modifyUserRole
    function modifyUserRole($iduser, $role) {
        if ($iduser==null || $iduser=="") {
            echo json_encode(
                array("error" => "Utente da modificare non riconosciuto. Riprovare")
                );
            return;
        }
        
        if ($role==null || $role=="") {
            return;
        }
        
        if ($role != "ROLE_ADMIN") {
            $role = "ROLE_USER";
        }
        
        $query = "UPDATE " . $this->table_name_users_roles . " SET id_role = '" . $role . "' WHERE id_user = " . $iduser;
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    // deleteUser
    function deleteUser($idUser){
        $query = "DELETE u, ur FROM " . $this->table_name_users . " u,  " . $this->table_name_users_roles . " ur WHERE u.id_user = ur.id_user AND u.id_user = " . $idUser;        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
}