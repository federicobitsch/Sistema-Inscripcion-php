<?php
//conexión a MySQL
class Database {
    private $host = 'localhost';
    private $db   = 'sistema_inscripcion';
    private $user = 'root';
    private $pass = '';
    public  $conn;

    public function __construct() {
        $this->connect();
    }

    public function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
        return $this->conn;
    }
}
