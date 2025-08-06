<?php
class AlumnoModel {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    // Registrar alumno
    public function registrar($nombre, $ap, $am, $curp, $id_tutor, $nivel, $grado) {
        $sql = "SELECT registrar_alumno(:nombre, :ap, :am, :curp, :tutor, :nivel, :grado)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':ap', $ap);
        $stmt->bindParam(':am', $am);
        $stmt->bindParam(':curp', $curp);
        $stmt->bindParam(':tutor', $id_tutor);
        $stmt->bindParam(':nivel', $nivel);
        $stmt->bindParam(':grado', $grado);

        if ($stmt->execute()) {
            return $stmt->fetchColumn();
        } else {
            print_r($stmt->errorInfo());
            return false;
        }
    }

    // Listar todos los alumnos
    public function listarTodos() {
        $sql = "SELECT * FROM Alumno ORDER BY id_alumno";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar alumnos por nombre o apellido
    public function buscarPorNombreApellido($termino) {
        $sql = "SELECT * FROM Alumno WHERE 
                    nombre ILIKE :term OR 
                    apellido_p ILIKE :term OR 
                    apellido_m ILIKE :term
                ORDER BY id_alumno";
        $stmt = $this->conn->prepare($sql);
        $term = "%$termino%";
        $stmt->execute([':term' => $term]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id_alumno) {
        $sql = "SELECT * FROM Alumno WHERE id_alumno = :id_alumno";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_alumno', $id_alumno);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar alumno
    public function actualizar($id_alumno, $nombre, $apellido_p, $apellido_m, $curp, $id_tutor) {
        $sql = "UPDATE Alumno SET 
                    nombre = :nombre, 
                    apellido_p = :apellido_p, 
                    apellido_m = :apellido_m, 
                    curp = :curp, 
                    id_tutor = :id_tutor 
                WHERE id_alumno = :id_alumno";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido_p', $apellido_p);
        $stmt->bindParam(':apellido_m', $apellido_m);
        $stmt->bindParam(':curp', $curp);
        $stmt->bindParam(':id_tutor', $id_tutor);
        $stmt->bindParam(':id_alumno', $id_alumno);

        if ($stmt->execute()) {
            return true;
        } else {
            print_r($stmt->errorInfo());
            return false;
        }
    }

    // Eliminar alumno
    public function eliminar($id) {
        $sql = "DELETE FROM Alumno WHERE id_alumno = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        } else {
            print_r($stmt->errorInfo());
            return false;
        }
    }
}