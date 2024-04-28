<?php
class conn {
    private $conn;
    private $host = "localhost";
    private $user = "admin";
    private $pass = "admin";
    private $dbname = "surveyframework";

    public function __construct() {
        try{
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8";
            $pdo = new PDO($dsn, $this->user, $this->pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn = $pdo;
        } catch(Exception $e){
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function get_connection() {
        return $this->conn;
    }
}
?>
