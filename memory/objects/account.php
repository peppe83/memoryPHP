<?php
class Account{
 
    // database connection and table name
    private $conn;
    private $table_name_account = "account";
    private $table_name_type_account = "type_account";
 
    // object properties
    public $id_file;
    public $id_db;
    public $id_user;
    public $id_type;
    public $link;
    public $username;
    public $password;
    public $opt;
    public $keysearch;
    public $del;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read products
    function readAll($usernameURL, $usernameParentURL, $keysearchURL, $typeAccountURL){
        
        $extraWhere = "";
        
        if($typeAccountURL != "*"){
            $extraWhere .= " and id_type = " . $typeAccountURL;
        }
        
        if($keysearchURL != "*"){
            $extraWhere .= " and keysearch LIKE '%" . $keysearchURL . "%'";
        }
        
        if($usernameURL == $usernameParentURL){
        }else{
            $extraWhere .= " and (SELECT u.id_user FROM " . $this->table_name_users . " u, " . $this->table_name_users_roles . " ur WHERE ur.id_user = u.id_user 
                            and 'ROLE_ADMIN' = ur.id_role and u.id_user='" . $usernameParentURL . "')";
        }
        
        $query = "SELECT a.id_file, a.id_db, a.id_user, a.id_type, a.link, a.username, a.password, a.opt, a.keysearch, a.del
        from " . $this->table_name_account . " a where a.id_user = '" . $usernameURL . "' " . $extraWhere;          //a.del is false and
    
    	// prepare query statement
    	$stmt = $this->conn->prepare($query);
    
    	// execute query
    	$stmt->execute();
    
    	return $stmt;
    }
    
    
    function addAccount($idFileURL, $idUserURL, $usernameURL, $passwordURL, $keysearchURL, $typeAccountURL, $linkURL, $optURL, $deleteURL){
        $query = "INSERT INTO " . $this->table_name_account . " (id_file, id_user, id_type, link, username, password, opt, keysearch, del)
            VALUES (" . $idFileURL . ", " . $idUserURL . ", " . $typeAccountURL . ", '" . $linkURL . "', '" . $usernameURL . "',
                     '" . $passwordURL . "', '" . $optURL . "', '" . $keysearchURL . "', " . $deleteURL . ")";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    function removeAccount($iddb){
        $query = "UPDATE " . $this->table_name_account . " SET del = true WHERE id_db = " . $iddb;

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
}