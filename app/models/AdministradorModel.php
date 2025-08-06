<?php
class AdministradorModel {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrar($nombre, $ap, $am) {
        $sql = "SELECT registrar_administrador(:nombre, :ap, :am)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':ap', $ap);
        $stmt->bindParam(':am', $am);

        try {
            if ($stmt->execute()) {
                return $stmt->fetchColumn();
            } else {
                print_r($stmt->errorInfo());
                return false;
            }
        } catch (PDOException $e) {
            echo "❌ Error en la base de datos: " . $e->getMessage();
            return false;
        }        
    }
}
?>
