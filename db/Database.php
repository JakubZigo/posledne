<?php
require_once "config.php";
class Database {
    private $servername = "localhost";
    private $username = "xzigoj";
    private $password = "iOJ75sD0tFIIMRz";
    private $dbname = "posledne";
    public function getConnection() {
        try {
            $db = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
