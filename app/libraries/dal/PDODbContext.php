<?php
class PDODbContext extends PDO
{
    public function __construct($hostName, $dbName, $userName, $password) {
        $dsn = 'mysql:host='.$hostName.';dbname='.$dbName;
        $username = $userName;
        $password = $password;
        
        try {
            $options = array(1002 => 'SET NAMES utf8');
            
                parent::__construct($dsn, $username, $password, $options);
            
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            throw new Exception(501, 'MySQL: ' . $e->getMessage());
        }
        
        return $this;
    }
}