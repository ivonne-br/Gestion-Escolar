<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'gestion_escolar_nueva';
    private $username = 'ivonne';
    private $password = 'ajolote';
    public $conn;

    public function conectar() {
        $this->conn = null;
        try {
            $this->conn = new PDO("pgsql:host=$this->host;dbname=$this->db_name",
                                  $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
        return $this->conn;
    }
}
?>