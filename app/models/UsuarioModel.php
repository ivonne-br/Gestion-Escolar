<?php
class UsuarioModel {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    // Login para usuario
    public function buscarUsuario($id_usuario) {
        $sql = "SELECT * FROM Usuario WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Asignar contraseña a usuario
    public function actualizarContrasena($id, $contrasena_hash) {
        try {
            $sql = "UPDATE Usuario SET contrasena = :contrasena WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':contrasena' => $contrasena_hash,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            echo "❌ Error al actualizar contraseña: " . $e->getMessage();
            return false;
        }
    }






    

    // se usa???
    public function crearUsuarioSinContrasena($id, $rol) {
        try {
            $sql = "INSERT INTO Usuario (id_usuario, rol) VALUES (:id, :rol)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':rol' => $rol
            ]);
        } catch (PDOException $e) {
            echo "❌ Error al crear usuario sin contraseña: " . $e->getMessage();
            return false;
        }
    }

    // se usa???
    public function existeUsuario($id) {
        $sql = "SELECT COUNT(*) FROM Usuario WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn() > 0;
    }

    
}
