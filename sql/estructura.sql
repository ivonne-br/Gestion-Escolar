-- Proyecto: Gestión Escolar
-- Autor: Karina Ivonne B. Rojas

-- 🔧 ENUMS
CREATE TYPE rol_usuario AS ENUM ('alumno', 'profesor', 'tutor', 'administrador');

-- 👤 Usuarios
CREATE TABLE Usuario (
  id_usuario VARCHAR(6) PRIMARY KEY,
  rol rol_usuario NOT NULL,
  contrasena VARCHAR(10) NOT NULL DEFAULT ''
);

-- 📅 Ciclo Escolar
CREATE TABLE CicloEscolar (
    id_ciclo SERIAL PRIMARY KEY,
    nombre_ciclo VARCHAR(9) UNIQUE,
    fecha_inicio DATE,
    fecha_fin DATE,
    es_actual BOOLEAN DEFAULT FALSE
);

-- 🧑‍🏫 Profesor
CREATE TABLE Profesor (
  id_profesor VARCHAR(6) PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  apellido_p VARCHAR(50),
  apellido_m VARCHAR(50),
  correo VARCHAR(50) UNIQUE,
  especialidad VARCHAR(50),
  id_usuario VARCHAR(6) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);

-- 👪 Tutor
CREATE TABLE Tutor (
  id_tutor VARCHAR(6) PRIMARY KEY,
  nombre VARCHAR(50),
  apellido_p VARCHAR(50),
  apellido_m VARCHAR(50),
  correo VARCHAR(50) UNIQUE,
  telefono VARCHAR(10),
  id_usuario VARCHAR(6) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);

-- 👧 Alumno
CREATE TABLE Alumno (
  id_alumno VARCHAR(6) PRIMARY KEY,
  nombre VARCHAR(50),
  apellido_p VARCHAR(50),
  apellido_m VARCHAR(50),
  correo VARCHAR(50) UNIQUE,
  curp VARCHAR(18) UNIQUE,
  id_usuario VARCHAR(6) REFERENCES Usuario(id_usuario) ON DELETE CASCADE,
  id_tutor VARCHAR(6) REFERENCES Tutor(id_tutor) ON DELETE SET NULL,
  grado INT,
  nivel VARCHAR(20)
);

-- 📚 Materia
CREATE TABLE Materia (
    id_materia VARCHAR(6) PRIMARY KEY,
    nombre VARCHAR(50),
    nivel VARCHAR(10),
    grado INT,
    especialidad VARCHAR(50)
);

-- 🏫 Grupo
CREATE TABLE Grupo (
  id_grupo SERIAL PRIMARY KEY,
  nivel VARCHAR(50),
  grado INT,
  letra CHAR(1),
  id_ciclo INT REFERENCES CicloEscolar(id_ciclo)
);

-- 🔗 Relación Profesor - Grupo - Materia
CREATE TABLE ProfesorGrupoMateria (
  id_profesor VARCHAR(6),
  id_grupo INT,
  id_materia VARCHAR(6),
  PRIMARY KEY (id_profesor, id_grupo, id_materia),
  FOREIGN KEY (id_profesor) REFERENCES Profesor(id_profesor) ON DELETE CASCADE,
  FOREIGN KEY (id_grupo) REFERENCES Grupo(id_grupo) ON DELETE CASCADE,
  FOREIGN KEY (id_materia) REFERENCES Materia(id_materia) ON DELETE CASCADE
);

-- 📅 Periodo
CREATE TABLE Periodo (
    id_periodo SERIAL PRIMARY KEY,
    nombre VARCHAR(10) NOT NULL CHECK (nombre IN ('Primer', 'Segundo', 'Tercer')),
    orden SMALLINT NOT NULL CHECK (orden BETWEEN 1 AND 3),
    id_ciclo INT NOT NULL REFERENCES CicloEscolar(id_ciclo),
    fecha_inicio DATE,
    fecha_fin DATE,
    estado VARCHAR(10) DEFAULT 'abierto' CHECK (estado IN ('abierto', 'cerrado'))
);

-- 📝 Boleta
CREATE TABLE Boleta (
    id_alumno VARCHAR(6),
    id_ciclo INT,
    calif_final_general INT,
    observaciones TEXT,
    PRIMARY KEY (id_alumno, id_ciclo),
    FOREIGN KEY (id_alumno) REFERENCES Alumno(id_alumno),
    FOREIGN KEY (id_ciclo) REFERENCES CicloEscolar(id_ciclo)
);

-- 🏆 Calificación Materia
CREATE TABLE CalificacionMateria (
    id_alumno VARCHAR(6),
    id_ciclo INT,
    id_materia VARCHAR(6),
    id_periodo INT,
    calif_final INT,
    observaciones TEXT,
    fecha_registro DATE,
    PRIMARY KEY (id_alumno, id_materia, id_ciclo, id_periodo),
    FOREIGN KEY (id_alumno, id_ciclo) REFERENCES Boleta(id_alumno, id_ciclo),
    FOREIGN KEY (id_materia) REFERENCES Materia(id_materia),
    FOREIGN KEY (id_periodo) REFERENCES Periodo(id_periodo)
);

-- ✅ Tarea
CREATE TABLE Tarea (
    id_tarea SERIAL PRIMARY KEY,
    nombre VARCHAR(50),
    descripcion TEXT,
    fecha_entrega DATE,
    id_materia VARCHAR(6) REFERENCES Materia(id_materia),
    id_grupo INT REFERENCES Grupo(id_grupo)
);

-- ✅ Calificación Tarea
CREATE TABLE CalificacionTarea (
    id_tarea INT REFERENCES Tarea(id_tarea),
    id_alumno VARCHAR(6) REFERENCES Alumno(id_alumno),
    calificacion FLOAT,
    observaciones TEXT,
    fecha_registro DATE,
    PRIMARY KEY (id_tarea, id_alumno)
);

-- 👨‍👩‍👧 Asignación Grupo Alumno
CREATE TABLE AsignacionGrupoAlumno (
  id_alumno VARCHAR(6),
  id_grupo INT,
  id_ciclo INT,
  PRIMARY KEY (id_alumno, id_ciclo),
  FOREIGN KEY (id_alumno) REFERENCES Alumno(id_alumno) ON DELETE CASCADE,
  FOREIGN KEY (id_grupo) REFERENCES Grupo(id_grupo) ON DELETE SET NULL,
  FOREIGN KEY (id_ciclo) REFERENCES CicloEscolar(id_ciclo)
);

-- 📄 Administrador
CREATE TABLE Administrador (
  id_admin VARCHAR(6) PRIMARY KEY,
  nombre VARCHAR(50),
  apellido_p VARCHAR(50),
  apellido_m VARCHAR(50),
  correo VARCHAR(50) UNIQUE,
  id_usuario VARCHAR(6) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);

-- Datos de materias
-- Primaria (Grados 1 a 6)
INSERT INTO Materia (id_materia, nombre, nivel, grado, especialidad) VALUES
-- PRIMERO
('ESP1', 'Español I', 'Primaria', 1, 'Español'),
('MAT1', 'Matemáticas I', 'Primaria', 1, 'Matemáticas'),
('ENS1', 'Exploración de la Naturaleza y la Sociedad I', 'Primaria', 1, 'Ciencias'),
('FCE1', 'Formación Cívica y Ética I', 'Primaria', 1, 'Formación Cívica y Ética'),
('ART1', 'Educación Artística I', 'Primaria', 1, 'Artes'),
('EDU1', 'Educación Física I', 'Primaria', 1, 'Educación Física'),
('ING1', 'Inglés I', 'Primaria', 1, 'Inglés'),

-- SEGUNDO
('ESP2', 'Español II', 'Primaria', 2, 'Español'),
('MAT2', 'Matemáticas II', 'Primaria', 2, 'Matemáticas'),
('ENS2', 'Exploración de la Naturaleza y la Sociedad II', 'Primaria', 2, 'Ciencias'),
('FCE2', 'Formación Cívica y Ética II', 'Primaria', 2, 'Formación Cívica y Ética'),
('ART2', 'Educación Artística II', 'Primaria', 2, 'Artes'),
('EDU2', 'Educación Física II', 'Primaria', 2, 'Educación Física'),
('ING2', 'Inglés II', 'Primaria', 2, 'Inglés'),

-- TERCERO
('ESP3', 'Español III', 'Primaria', 3, 'Español'),
('MAT3', 'Matemáticas III', 'Primaria', 3, 'Matemáticas'),
('CNA1', 'Ciencias Naturales I', 'Primaria', 3, 'Ciencias'),
('EDV1', 'La Entidad donde Vivo', 'Primaria', 3, 'Geografía'),
('FCE3', 'Formación Cívica y Ética III', 'Primaria', 3, 'Formación Cívica y Ética'),
('ART3', 'Educación Artística III', 'Primaria', 3, 'Artes'),
('EDU3', 'Educación Física III', 'Primaria', 3, 'Educación Física'),
('ING3', 'Inglés III', 'Primaria', 3, 'Inglés'),

-- CUARTO
('ESP4', 'Español IV', 'Primaria', 4, 'Español'),
('MAT4', 'Matemáticas IV', 'Primaria', 4, 'Matemáticas'),
('CNA2', 'Ciencias Naturales II', 'Primaria', 4, 'Ciencias'),
('GEO1', 'Geografía I', 'Primaria', 4, 'Geografía'),
('HIS1', 'Historia I', 'Primaria', 4, 'Historia'),
('FCE4', 'Formación Cívica y Ética IV', 'Primaria', 4, 'Formación Cívica y Ética'),
('ART4', 'Educación Artística IV', 'Primaria', 4, 'Artes'),
('EDU4', 'Educación Física IV', 'Primaria', 4, 'Educación Física'),
('ING4', 'Inglés IV', 'Primaria', 4, 'Inglés'),

-- QUINTO
('ESP5', 'Español V', 'Primaria', 5, 'Español'),
('MAT5', 'Matemáticas V', 'Primaria', 5, 'Matemáticas'),
('CNA3', 'Ciencias Naturales III', 'Primaria', 5, 'Ciencias'),
('GEO2', 'Geografía II', 'Primaria', 5, 'Geografía'),
('HIS2', 'Historia II', 'Primaria', 5, 'Historia'),
('FCE5', 'Formación Cívica y Ética V', 'Primaria', 5, 'Formación Cívica y Ética'),
('ART5', 'Educación Artística V', 'Primaria', 5, 'Artes'),
('EDU5', 'Educación Física V', 'Primaria', 5, 'Educación Física'),
('ING5', 'Inglés V', 'Primaria', 5, 'Inglés'),

-- SEXTO
('ESP6', 'Español VI', 'Primaria', 6, 'Español'),
('MAT6', 'Matemáticas VI', 'Primaria', 6, 'Matemáticas'),
('CNA4', 'Ciencias Naturales IV', 'Primaria', 6, 'Ciencias'),
('GEO3', 'Geografía III', 'Primaria', 6, 'Geografía'),
('HIS3', 'Historia III', 'Primaria', 6, 'Historia'),
('FCE6', 'Formación Cívica y Ética VI', 'Primaria', 6, 'Formación Cívica y Ética'),
('ART6', 'Educación Artística VI', 'Primaria', 6, 'Artes'),
('EDU6', 'Educación Física VI', 'Primaria', 6, 'Educación Física'),
('ING6', 'Inglés VI', 'Primaria', 6, 'Inglés');

-- Secundaria (Grados 1 a 3)
INSERT INTO Materia (id_materia, nombre, nivel, grado, especialidad) VALUES
-- PRIMER GRADO
('ESP7', 'Español I', 'Secundaria', 1, 'Español'),
('MAT7', 'Matemáticas I', 'Secundaria', 1, 'Matemáticas'),
('CIE1', 'Biología', 'Secundaria', 1, 'Ciencias'),
('GEO4', 'Geografía de México y del Mundo', 'Secundaria', 1, 'Geografía'),
('EDF7', 'Educación Física I', 'Secundaria', 1, 'Educación Física'),
('ART7', 'Artes I', 'Secundaria', 1, 'Artes'),
('TEC1', 'Tecnología I', 'Secundaria', 1, 'Tecnología'),
('ING7', 'Inglés I', 'Secundaria', 1, 'Inglés'),

-- SEGUNDO GRADO
('ESP8', 'Español II', 'Secundaria', 2, 'Español'),
('MAT8', 'Matemáticas II', 'Secundaria', 2, 'Matemáticas'),
('CIE2', 'Física', 'Secundaria', 2, 'Ciencias'),
('HIS4', 'Historia I', 'Secundaria', 2, 'Historia'),
('FCE7', 'Formación Cívica y Ética I', 'Secundaria', 2, 'Formación Cívica y Ética'),
('EDF8', 'Educación Física II', 'Secundaria', 2, 'Educación Física'),
('ART8', 'Artes II', 'Secundaria', 2, 'Artes'),
('TEC2', 'Tecnología II', 'Secundaria', 2, 'Tecnología'),
('ING8', 'Inglés II', 'Secundaria', 2, 'Inglés'),

-- TERCER GRADO
('ESP9', 'Español III', 'Secundaria', 3, 'Español'),
('MAT9', 'Matemáticas III', 'Secundaria', 3, 'Matemáticas'),
('CIE3', 'Química', 'Secundaria', 3, 'Ciencias'),
('HIS5', 'Historia II', 'Secundaria', 3, 'Historia'),
('FCE8', 'Formación Cívica y Ética II', 'Secundaria', 3, 'Formación Cívica y Ética'),
('EDF9', 'Educación Física III', 'Secundaria', 3, 'Educación Física'),
('ART9', 'Artes III', 'Secundaria', 3, 'Artes'),
('TEC3', 'Tecnología III', 'Secundaria', 3, 'Tecnología'),
('ING9', 'Inglés III', 'Secundaria', 3, 'Inglés');

-- 🔁 SECUENCIAS
DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_class WHERE relkind = 'S' AND relname = 'tutor_id_seq') THEN
        CREATE SEQUENCE tutor_id_seq START WITH 1;
    END IF;
END$$;

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_class WHERE relkind = 'S' AND relname = 'profesor_id_seq') THEN
        CREATE SEQUENCE profesor_id_seq START WITH 1;
    END IF;
END$$;

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_class WHERE relkind = 'S' AND relname = 'alumno_id_seq') THEN
        CREATE SEQUENCE alumno_id_seq START WITH 1;
    END IF;
END$$;

-- 📥 FUNCIONES DE REGISTRO
-- Función: Registrar Tutor
CREATE OR REPLACE FUNCTION registrar_tutor(
    p_nombre VARCHAR,
    p_apellido_p VARCHAR,
    p_apellido_m VARCHAR,
    p_correo VARCHAR,
    p_telefono VARCHAR,
    p_contrasena VARCHAR
) RETURNS VARCHAR AS $$
DECLARE
    nuevo_id VARCHAR(6);
BEGIN
    -- Generar nuevo id_tutor
    nuevo_id := 'T' || LPAD(nextval('tutor_id_seq')::TEXT, 5, '0');

    -- Insertar en Usuario
    INSERT INTO Usuario(id_usuario, rol, contrasena)
    VALUES (nuevo_id, 'tutor', p_contrasena);

    -- Insertar en Tutor
    INSERT INTO Tutor(id_tutor, nombre, apellido_p, apellido_m, correo, telefono, id_usuario)
    VALUES (nuevo_id, p_nombre, p_apellido_p, p_apellido_m, p_correo, p_telefono, nuevo_id);

    RETURN nuevo_id;
END
$$ LANGUAGE plpgsql;

-- Función: Registrar Profesor
CREATE OR REPLACE FUNCTION registrar_profesor(
    p_nombre VARCHAR,
    p_apellido_p VARCHAR,
    p_apellido_m VARCHAR,
    p_correo VARCHAR,
    p_especialidad VARCHAR,
    p_contrasena VARCHAR
) RETURNS VARCHAR AS $$
DECLARE
    nuevo_id VARCHAR(6);
BEGIN
    -- Generar nuevo id_profesor
    nuevo_id := 'P' || LPAD(nextval('profesor_id_seq')::TEXT, 5, '0');

    -- Insertar en Usuario
    INSERT INTO Usuario(id_usuario, rol, contrasena)
    VALUES (nuevo_id, 'profesor', p_contrasena);

    -- Insertar en Profesor
    INSERT INTO Profesor(id_profesor, nombre, apellido_p, apellido_m, correo, especialidad, id_usuario)
    VALUES (nuevo_id, p_nombre, p_apellido_p, p_apellido_m, p_correo, p_especialidad, nuevo_id);

    RETURN nuevo_id;
END
$$ LANGUAGE plpgsql;

-- Función: Registrar Alumno (con grado y nivel)
CREATE OR REPLACE FUNCTION registrar_alumno(
    p_nombre VARCHAR,
    p_apellido_p VARCHAR,
    p_apellido_m VARCHAR,
    p_correo VARCHAR,
    p_curp VARCHAR,
    p_id_tutor VARCHAR,
    p_grado INT,
    p_nivel VARCHAR,
    p_contrasena VARCHAR
) RETURNS VARCHAR AS $$
DECLARE
    nuevo_id VARCHAR(6);
BEGIN
    -- Generar nuevo id_alumno
    nuevo_id := 'A' || LPAD(nextval('alumno_id_seq')::TEXT, 5, '0');

    -- Insertar en Usuario
    INSERT INTO Usuario(id_usuario, rol, contrasena)
    VALUES (nuevo_id, 'alumno', p_contrasena);

    -- Insertar en Alumno
    INSERT INTO Alumno(id_alumno, nombre, apellido_p, apellido_m, correo, curp, id_usuario, id_tutor, grado, nivel)
    VALUES (nuevo_id, p_nombre, p_apellido_p, p_apellido_m, p_correo, p_curp, nuevo_id, p_id_tutor, p_grado, p_nivel);

    RETURN nuevo_id;
END
$$ LANGUAGE plpgsql;

-- Función: Registrar Administrador
CREATE OR REPLACE FUNCTION registrar_administrador(
    p_nombre VARCHAR,
    p_apellido_p VARCHAR,
    p_apellido_m VARCHAR,
    p_correo VARCHAR,
    p_contrasena VARCHAR
) RETURNS VARCHAR AS $$
DECLARE
    nuevo_id VARCHAR(6);
BEGIN
    -- Generar nuevo id_admin
    nuevo_id := 'AD' || LPAD(nextval('alumno_id_seq')::TEXT, 4, '0');

    -- Insertar en Usuario
    INSERT INTO Usuario(id_usuario, rol, contrasena)
    VALUES (nuevo_id, 'administrador', p_contrasena);

    -- Insertar en Administrador
    INSERT INTO Administrador(id_admin, nombre, apellido_p, apellido_m, correo, id_usuario)
    VALUES (nuevo_id, p_nombre, p_apellido_p, p_apellido_m, p_correo, nuevo_id);

    RETURN nuevo_id;
END
$$ LANGUAGE plpgsql;

-- 🧮 FUNCIONES DE ASIGNACIÓN
-- Función: Preview de asignación
CREATE OR REPLACE FUNCTION preview_asignacion_grupos(
    p_id_ciclo INT,
    p_nivel VARCHAR
) RETURNS TABLE(id_alumno VARCHAR, nombre VARCHAR, grado INT, grupo CHAR(1)) AS $$
BEGIN
    RETURN QUERY
    SELECT a.id_alumno, a.nombre, a.grado, g.letra
    FROM Alumno a
    LEFT JOIN AsignacionGrupoAlumno aga
        ON a.id_alumno = aga.id_alumno AND aga.id_ciclo = p_id_ciclo
    LEFT JOIN Grupo g
        ON aga.id_grupo = g.id_grupo
    WHERE a.nivel = p_nivel;
END
$$ LANGUAGE plpgsql;

-- Función: Asignar Grupos Confirmado
CREATE OR REPLACE FUNCTION asignar_grupos_confirmado(
    p_id_alumno VARCHAR,
    p_id_grupo INT,
    p_id_ciclo INT
) RETURNS VOID AS $$
BEGIN
    INSERT INTO AsignacionGrupoAlumno(id_alumno, id_grupo, id_ciclo)
    VALUES (p_id_alumno, p_id_grupo, p_id_ciclo)
    ON CONFLICT (id_alumno, id_ciclo) DO UPDATE
    SET id_grupo = EXCLUDED.id_grupo;
END
$$ LANGUAGE plpgsql;

-- Función: Promocionar Grupos al Siguiente Ciclo
CREATE OR REPLACE FUNCTION promocionar_grupos_al_siguiente_ciclo(
    p_id_ciclo_actual INT,
    p_id_ciclo_nuevo INT
) RETURNS VOID AS $$
DECLARE
    rec RECORD;
    nuevo_grado INT;
    nuevo_grupo INT;
BEGIN
    FOR rec IN
        SELECT aga.id_alumno, g.nivel, g.grado, g.letra
        FROM AsignacionGrupoAlumno aga
        JOIN Grupo g ON aga.id_grupo = g.id_grupo
        WHERE aga.id_ciclo = p_id_ciclo_actual
    LOOP
        -- Calcular nuevo grado
        nuevo_grado := rec.grado + 1;
        -- Buscar grupo con mismo nivel, nuevo grado y misma letra en el nuevo ciclo
        SELECT id_grupo INTO nuevo_grupo
        FROM Grupo
        WHERE nivel = rec.nivel AND grado = nuevo_grado AND letra = rec.letra AND id_ciclo = p_id_ciclo_nuevo;

        IF nuevo_grupo IS NOT NULL THEN
            INSERT INTO AsignacionGrupoAlumno(id_alumno, id_grupo, id_ciclo)
            VALUES (rec.id_alumno, nuevo_grupo, p_id_ciclo_nuevo);
        END IF;
    END LOOP;
END
$$ LANGUAGE plpgsql;

-- Función: Obtener Egresados para Promoción
CREATE OR REPLACE FUNCTION obtener_egresados_para_promocion(
    p_id_ciclo INT,
    p_nivel VARCHAR,
    p_grado INT
) RETURNS TABLE(id_alumno VARCHAR, nombre VARCHAR, apellido_p VARCHAR, apellido_m VARCHAR) AS $$
BEGIN
    RETURN QUERY
    SELECT a.id_alumno, a.nombre, a.apellido_p, a.apellido_m
    FROM Alumno a
    JOIN AsignacionGrupoAlumno aga ON a.id_alumno = aga.id_alumno
    JOIN Grupo g ON aga.id_grupo = g.id_grupo
    WHERE g.nivel = p_nivel AND g.grado = p_grado AND aga.id_ciclo = p_id_ciclo;
END
$$ LANGUAGE plpgsql;

-- Procedimiento: Asignar grupos secundaria a egresados
CREATE OR REPLACE FUNCTION asignar_grupos_secundaria_egresados(
    p_id_ciclo INT,
    p_id_grupo INT,
    p_lista_alumnos VARCHAR[]
) RETURNS VOID AS $$
DECLARE
    alumno_id VARCHAR;
BEGIN
    FOREACH alumno_id IN ARRAY p_lista_alumnos
    LOOP
        INSERT INTO AsignacionGrupoAlumno(id_alumno, id_grupo, id_ciclo)
        VALUES (alumno_id, p_id_grupo, p_id_ciclo)
        ON CONFLICT (id_alumno, id_ciclo) DO UPDATE
        SET id_grupo = EXCLUDED.id_grupo;
    END LOOP;
END
$$ LANGUAGE plpgsql;

-- Procedimiento: Asignar profesores a materias por especialidad
CREATE OR REPLACE FUNCTION asignar_profesores_a_materias_por_especialidad()
RETURNS VOID AS $$
DECLARE
    rec RECORD;
    profesor_id VARCHAR(6);
BEGIN
    FOR rec IN
        SELECT g.id_grupo, m.id_materia, m.especialidad
        FROM Grupo g
        JOIN Materia m ON g.nivel = m.nivel AND g.grado = m.grado
    LOOP
        -- Buscar profesor con especialidad
        SELECT id_profesor INTO profesor_id
        FROM Profesor
        WHERE especialidad = rec.especialidad
        LIMIT 1;

        IF profesor_id IS NOT NULL THEN
            INSERT INTO ProfesorGrupoMateria(id_profesor, id_grupo, id_materia)
            VALUES (profesor_id, rec.id_grupo, rec.id_materia)
            ON CONFLICT DO NOTHING;
        END IF;
    END LOOP;
END
$$ LANGUAGE plpgsql;

-- Procedimiento: Asignar profesores a materias por especialidad con retorno
CREATE OR REPLACE FUNCTION asignar_profesores_a_materias_por_especialidad_con_retorno()
RETURNS TABLE(id_grupo INT, id_materia VARCHAR, id_profesor VARCHAR) AS $$
DECLARE
    rec RECORD;
    profesor_id VARCHAR(6);
BEGIN
    FOR rec IN
        SELECT g.id_grupo, m.id_materia, m.especialidad
        FROM Grupo g
        JOIN Materia m ON g.nivel = m.nivel AND g.grado = m.grado
    LOOP
        -- Buscar profesor con especialidad
        SELECT id_profesor INTO profesor_id
        FROM Profesor
        WHERE especialidad = rec.especialidad
        LIMIT 1;

        IF profesor_id IS NOT NULL THEN
            INSERT INTO ProfesorGrupoMateria(id_profesor, id_grupo, id_materia)
            VALUES (profesor_id, rec.id_grupo, rec.id_materia)
            ON CONFLICT DO NOTHING;
            RETURN NEXT (rec.id_grupo, rec.id_materia, profesor_id);
        END IF;
    END LOOP;
END
$$ LANGUAGE plpgsql;

-- 📅 CICLOS Y PERIODOS
-- Modificar Periodo
ALTER TABLE Periodo
ADD COLUMN fecha_inicio DATE,
ADD COLUMN fecha_fin DATE,
ADD COLUMN estado VARCHAR(10) DEFAULT 'abierto' CHECK (estado IN ('abierto', 'cerrado'));

-- Procedimiento: Crear ciclo escolar
CREATE OR REPLACE FUNCTION crear_ciclo_escolar(
    p_nombre_ciclo VARCHAR,
    p_fecha_inicio DATE,
    p_fecha_fin DATE
) RETURNS INT AS $$
DECLARE
    nuevo_id INT;
BEGIN
    INSERT INTO CicloEscolar(nombre_ciclo, fecha_inicio, fecha_fin, es_actual)
    VALUES (p_nombre_ciclo, p_fecha_inicio, p_fecha_fin, TRUE)
    RETURNING id_ciclo INTO nuevo_id;
    RETURN nuevo_id;
END
$$ LANGUAGE plpgsql;

-- 📝 CALIFICACIONES Y BOLETAS
-- Procedimiento: Calificar tarea
CREATE OR REPLACE FUNCTION registrar_calificacion_tarea(
    p_id_tarea INT,
    p_id_alumno VARCHAR,
    p_calificacion FLOAT,
    p_observaciones TEXT
) RETURNS VOID AS $$
BEGIN
    INSERT INTO CalificacionTarea(id_tarea, id_alumno, calificacion, observaciones, fecha_registro)
    VALUES (p_id_tarea, p_id_alumno, p_calificacion, p_observaciones, CURRENT_DATE)
    ON CONFLICT (id_tarea, id_alumno) DO UPDATE
    SET calificacion = EXCLUDED.calificacion,
        observaciones = EXCLUDED.observaciones,
        fecha_registro = EXCLUDED.fecha_registro;
END
$$ LANGUAGE plpgsql;

-- Función: Calcular promedio de tareas
CREATE OR REPLACE FUNCTION calcular_promedio_tareas_alumno_materia_periodo(
    p_id_alumno VARCHAR,
    p_id_materia VARCHAR,
    p_id_periodo INT
) RETURNS FLOAT AS $$
DECLARE
    promedio FLOAT;
BEGIN
    SELECT AVG(ct.calificacion) INTO promedio
    FROM CalificacionTarea ct
    JOIN Tarea t ON ct.id_tarea = t.id_tarea
    WHERE ct.id_alumno = p_id_alumno
      AND t.id_materia = p_id_materia
      AND t.id_grupo IN (
            SELECT id_grupo
            FROM AsignacionGrupoAlumno aga
            WHERE aga.id_alumno = p_id_alumno
        )
      AND t.id_tarea IN (
            SELECT id_tarea
            FROM Tarea
            WHERE id_materia = p_id_materia
        );
    RETURN promedio;
END
$$ LANGUAGE plpgsql;

-- Vista: vista_boleta_alumno
CREATE OR REPLACE VIEW vista_boleta_alumno AS
SELECT
    a.id_alumno,
    a.nombre AS nombre_alumno,
    a.apellido_p,
    a.apellido_m,
    c.nombre_ciclo,
    b.calif_final_general,
    b.observaciones,
    m.nombre AS materia,
    cm.calif_final,
    cm.observaciones AS obs_materia,
    p.nombre AS periodo,
    p.orden
FROM Boleta b
JOIN Alumno a ON b.id_alumno = a.id_alumno
JOIN CicloEscolar c ON b.id_ciclo = c.id_ciclo
LEFT JOIN CalificacionMateria cm ON b.id_alumno = cm.id_alumno AND b.id_ciclo = cm.id_ciclo
LEFT JOIN Materia m ON cm.id_materia = m.id_materia
LEFT JOIN Periodo p ON cm.id_periodo = p.id_periodo;

-- Procedimiento: Crear boletas por ciclo
CREATE OR REPLACE FUNCTION crear_boletas_por_ciclo(
    p_id_ciclo INT
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Boleta(id_alumno, id_ciclo)
    SELECT id_alumno, p_id_ciclo
    FROM Alumno
    WHERE id_alumno NOT IN (
        SELECT id_alumno FROM Boleta WHERE id_ciclo = p_id_ciclo
    );
END
$$ LANGUAGE plpgsql;

-- Procedimiento: Registrar calificaciones por materia/periodo
CREATE OR REPLACE FUNCTION registrar_calificacion_materia_periodo(
    p_id_alumno VARCHAR,
    p_id_ciclo INT,
    p_id_materia VARCHAR,
    p_id_periodo INT,
    p_calif_final INT,
    p_observaciones TEXT
) RETURNS VOID AS $$
BEGIN
    INSERT INTO CalificacionMateria(id_alumno, id_ciclo, id_materia, id_periodo, calif_final, observaciones, fecha_registro)
    VALUES (p_id_alumno, p_id_ciclo, p_id_materia, p_id_periodo, p_calif_final, p_observaciones, CURRENT_DATE)
    ON CONFLICT (id_alumno, id_materia, id_ciclo, id_periodo) DO UPDATE
    SET calif_final = EXCLUDED.calif_final,
        observaciones = EXCLUDED.observaciones,
        fecha_registro = EXCLUDED.fecha_registro;
END
$$ LANGUAGE plpgsql;

