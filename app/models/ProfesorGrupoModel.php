<?php
class ProfesorGrupoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function asignarProfesoresPorEspecialidad() {
        $sql = "CALL asignar_profesores_a_materias_por_especialidad()";
        $this->pdo->exec($sql);
    }
}