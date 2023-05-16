<?php
require_once "config.php";
class Database {
    private $servername = "db";
    private $username = "user";
    private $password = "pass";
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
