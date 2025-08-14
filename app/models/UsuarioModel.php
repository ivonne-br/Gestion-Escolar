<?php

class UsuarioModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function buscarUsuario($id_usuario) {
        $sql = "SELECT id, nombre, apellido_paterno, apellido_materno, contrasena, rol FROM Usuario WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarContrasena($id_usuario, $nueva_contrasena) {
        $sql = "UPDATE Usuario SET contrasena = :contrasena WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':contrasena', $nueva_contrasena);
        $stmt->bindParam(':id', $id_usuario);
        return $stmt->execute();
    }
}
