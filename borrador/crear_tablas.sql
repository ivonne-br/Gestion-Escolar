-- Usuarios
CREATE TYPE rol_usuario AS ENUM ('alumno', 'profesor', 'tutor', 'administrador');

CREATE TABLE Usuario (
  id_usuario VARCHAR(6) PRIMARY KEY,
  rol rol_usuario NOT NULL
);

-- Ciclo Escolar
CREATE TABLE CicloEscolar (
    id_ciclo SERIAL PRIMARY KEY,
    nombre_ciclo VARCHAR(9) UNIQUE,
    fecha_inicio DATE,
    fecha_fin DATE
);

-- Grupo
CREATE TABLE Grupo (
  id_grupo SERIAL PRIMARY KEY,
  nivel VARCHAR(50),
  grado INT,
  letra CHAR(1),
  id_ciclo INT REFERENCES CicloEscolar(id_ciclo)
);

-- Tutor
CREATE TABLE Tutor (
  id_tutor VARCHAR(6) PRIMARY KEY,
  nombre VARCHAR(50),
  apellido_p VARCHAR(50),
  apellido_m VARCHAR(50),
  correo VARCHAR(50) UNIQUE,
  telefono VARCHAR(10),
  id_usuario VARCHAR(6) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);

-- Alumno
CREATE TABLE Alumno (
  id_alumno VARCHAR(6) PRIMARY KEY,
  nombre VARCHAR(50),
  apellido_p VARCHAR(50),
  apellido_m VARCHAR(50),
  correo VARCHAR(50) UNIQUE,
  curp VARCHAR(18) UNIQUE,
  id_usuario VARCHAR(6) REFERENCES Usuario(id_usuario) ON DELETE CASCADE,
  id_tutor VARCHAR(6) REFERENCES Tutor(id_tutor)
);

-- Profesor
CREATE TABLE Profesor (
  id_profesor VARCHAR(6) PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  apellido_p VARCHAR(50),
  apellido_m VARCHAR(50),
  correo VARCHAR(50) UNIQUE,
  especializacion VARCHAR(50),
  id_usuario VARCHAR(6) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);

-- Administrador
CREATE TABLE Administrador (
  id_admin VARCHAR(6) PRIMARY KEY,
  nombre VARCHAR(50),
  apellido_p VARCHAR(50),
  apellido_m VARCHAR(50),
  correo VARCHAR(50) UNIQUE,
  id_usuario VARCHAR(6) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);

-- Materia
CREATE TABLE Materia (
    id_materia VARCHAR(6) PRIMARY KEY,
    nombre VARCHAR(50),
    nivel VARCHAR(10),
    grado INT
);

-- Relación Profesor - Grupo - Materia
CREATE TABLE ProfesorGrupoMateria (
  id_profesor VARCHAR(6) REFERENCES Profesor(id_profesor),
  id_grupo INT REFERENCES Grupo(id_grupo),
  id_materia VARCHAR(6) REFERENCES Materia(id_materia),
  PRIMARY KEY (id_profesor, id_grupo, id_materia)
);

-- Periodo
CREATE TABLE Periodo (
    id_periodo SERIAL PRIMARY KEY,
    nombre VARCHAR(10) NOT NULL CHECK (nombre IN ('Primer', 'Segundo', 'Tercer')),
    orden SMALLINT NOT NULL CHECK (orden BETWEEN 1 AND 3),
    id_ciclo INT NOT NULL REFERENCES CicloEscolar(id_ciclo)
);

-- Boleta 
CREATE TABLE Boleta (
    id_alumno VARCHAR(6) REFERENCES Alumno(id_alumno),
    id_ciclo INT REFERENCES CicloEscolar(id_ciclo),
    calif_final_general INT,
    observaciones TEXT,
    PRIMARY KEY (id_alumno, id_ciclo)
);

-- Calificación Materia
CREATE TABLE CalificacionMateria (
    id_alumno VARCHAR(6),
	id_ciclo INT,
    id_materia VARCHAR(6) REFERENCES Materia(id_materia),
    id_periodo INT REFERENCES Periodo(id_periodo),
    calif_final INT,
    observaciones TEXT,
    fecha_registro DATE,
    PRIMARY KEY (id_alumno, id_materia, id_ciclo, id_periodo),
	FOREIGN KEY (id_alumno, id_ciclo) REFERENCES Boleta(id_alumno, id_ciclo)
);

-- Tarea
CREATE TABLE Tarea (
    id_tarea SERIAL PRIMARY KEY,
    nombre VARCHAR(50),
    descripcion TEXT,
    fecha_entrega DATE,
    id_materia VARCHAR(6) REFERENCES Materia(id_materia),
    id_grupo INT REFERENCES Grupo(id_grupo)
);

-- Calificación Tarea
CREATE TABLE CalificacionTarea (
    id_tarea INT REFERENCES Tarea(id_tarea),
    id_alumno VARCHAR(6) REFERENCES Alumno(id_alumno),
    calificacion FLOAT,
    observaciones TEXT,
    fecha_registro DATE,
    PRIMARY KEY (id_tarea, id_alumno)
);

-- Pago
CREATE TABLE Pago (
    id_pago SERIAL PRIMARY KEY,
    fecha_pago DATE,
    monto FLOAT,
    concepto INT,
    folio VARCHAR(10) UNIQUE,
    id_alumno VARCHAR(6) REFERENCES Alumno(id_alumno)
);
-- DF: Folio -> Pago
CREATE TABLE FolioPago (
    folio VARCHAR(10) PRIMARY KEY,
    id_pago INT REFERENCES Pago(id_pago)
);

--------------

-- Asignar grupo
CREATE TABLE AsignacionGrupoAlumno (
  id_alumno VARCHAR(6) REFERENCES Alumno(id_alumno) ON DELETE CASCADE,
  id_grupo INT REFERENCES Grupo(id_grupo) ON DELETE SET NULL,
  id_ciclo INT REFERENCES CicloEscolar(id_ciclo),
  PRIMARY KEY (id_alumno, id_ciclo)
);

