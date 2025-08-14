<?php
class TutorModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
/*
    // Listar todos los tutores
    public function listarTodosPorId() {
        $sql = "SELECT * FROM Tutor ORDER BY id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
*/
    // Listar tutores con información del usuario asociada
        public function listarConDatosDeUsuario() {
            $sql = "SELECT 
                        t.usuario_id AS id_tutor,
                        u.nombre,
                        u.apellido_paterno,
                        u.apellido_materno,
                        u.correo,
                        t.telefono
                    FROM Tutor t
                    JOIN Usuario u ON t.usuario_id = u.id
                    ORDER BY u.apellido_paterno, u.nombre";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    // Buscar por nombre o apellido (busqueda flexible)
    public function buscarPorNombreApellido($termino) {
        $sql = "SELECT 
                    t.usuario_id AS id_tutor,
                    u.nombre,
                    u.apellido_paterno,
                    u.apellido_materno,
                    u.correo,
                    t.telefono
                FROM Tutor t
                JOIN Usuario u ON t.usuario_id = u.id
                WHERE 
                    u.nombre ILIKE :term OR 
                    u.apellido_paterno ILIKE :term OR 
                    u.apellido_materno ILIKE :term
                ORDER BY u.apellido_paterno, u.nombre";
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
    
    public function actualizar($id, $nombre, $apellido_p, $apellido_m, $correo, $telefono) {
        $sql = "UPDATE Usuario SET
                    nombre = :nombre,
                    apellido_paterno = :apellido_p,
                    apellido_materno = :apellido_m,
                    correo = :correo
                WHERE id = :usuario_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido_p' => $apellido_p,
            ':apellido_m' => $apellido_m,
            ':correo' => $correo,
            ':usuario_id' => $id
        ]);

        $sql2 = "UPDATE Tutor SET telefono = :telefono WHERE usuario_id = :usuario_id";
        $stmt2 = $this->pdo->prepare($sql2);
        return $stmt2->execute([
            ':telefono' => $telefono,
            ':usuario_id' => $id
        ]);
    }

    // Eliminar tutor por id
    public function eliminar($id) {
        $sql = "DELETE FROM Tutor WHERE usuario_id = :usuario_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':usuario_id' => $id]);
    }


    // Obtener tutor por ID
    public function obtenerPorId($id_tutor) {
        $sql = "SELECT 
                    t.usuario_id AS id,
                    u.nombre,
                    u.apellido_paterno,
                    u.apellido_materno,
                    u.correo,
                    t.telefono
                FROM Tutor t
                JOIN Usuario u ON t.usuario_id = u.id
                WHERE t.usuario_id = :id_tutor";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_tutor' => $id_tutor]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Listar todos los tutores (para select)
    public function listarTodos() {
        $sql = "SELECT 
                    t.usuario_id AS id_tutor,
                    u.nombre,
                    u.apellido_paterno,
                    u.apellido_materno,
                    u.correo,
                    t.telefono
                FROM Tutor t
                JOIN Usuario u ON t.usuario_id = u.id
                ORDER BY u.apellido_paterno, u.nombre";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}