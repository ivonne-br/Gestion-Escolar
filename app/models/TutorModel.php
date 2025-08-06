<?php
class TutorModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar todos los tutores
    public function listarTodosPorId() {
        $sql = "SELECT * FROM Tutor ORDER BY id_tutor";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar por nombre o apellido (busqueda flexible)
    public function buscarPorNombreApellido($termino) {
        $sql = "SELECT * FROM Tutor WHERE 
                nombre ILIKE :term OR 
                apellido_p ILIKE :term OR 
                apellido_m ILIKE :term
                ORDER BY apellido_p, nombre";
        $stmt = $this->pdo->prepare($sql);
        $term = "%$termino%";
        $stmt->execute([':term' => $term]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Registrar tutor
    public function registrar($nombre, $apellido_p, $apellido_m, $correo, $telefono) {
        $sql = "SELECT registrar_tutor(:nombre, :apellido_p, :apellido_m, :correo, :telefono)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido_p' => $apellido_p,
            ':apellido_m' => $apellido_m,
            ':correo' => $correo,
            ':telefono' => $telefono
        ]);
        return $stmt->fetchColumn(); // retorna el nuevo id_tutor
    }
    
    public function actualizar($id_tutor, $nombre, $apellido_p, $apellido_m, $correo, $telefono) {
        $sql = "UPDATE Tutor SET
                    nombre = :nombre,
                    apellido_p = :apellido_p,
                    apellido_m = :apellido_m,
                    correo = :correo,
                    telefono = :telefono
                WHERE id_tutor = :id_tutor";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':apellido_p' => $apellido_p,
            ':apellido_m' => $apellido_m,
            ':correo' => $correo,
            ':telefono' => $telefono,
            ':id_tutor' => $id_tutor
        ]);
    }

    // Eliminar tutor por id
    public function eliminar($id_tutor) {
        $sql = "DELETE FROM Tutor WHERE id_tutor = :id_tutor";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id_tutor' => $id_tutor]);
    }


    // Obtener tutor por ID
    public function obtenerPorId($id_tutor) {
        $sql = "SELECT * FROM Tutor WHERE id_tutor = :id_tutor";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_tutor' => $id_tutor]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Listar todos los tutores (para select)
    public function listarTodos() {
        $sql = "SELECT * FROM Tutor ORDER BY apellido_p, nombre";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}