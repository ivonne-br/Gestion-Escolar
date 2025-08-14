-- ESQUEMA
CREATE SEQUENCE IF NOT EXISTS usuario_id_seq START 1;

-- ENUM type for user roles
CREATE TYPE rol_usuario AS ENUM ('tutor', 'profesor', 'alumno', 'administrador');

-- Usuario
CREATE TABLE Usuario (
    id VARCHAR PRIMARY KEY DEFAULT nextval('usuario_id_seq'),
    nombre VARCHAR(30) NOT NULL,
    apellido_paterno VARCHAR(30) NOT NULL,
    apellido_materno VARCHAR(30),
    correo VARCHAR(50) UNIQUE NOT NULL,
    contrasena VARCHAR(100) NOT NULL DEFAULT '',
    rol rol_usuario NOT NULL
);

-- Tutor
CREATE TABLE Tutor (
    id SERIAL PRIMARY KEY,
    usuario_id VARCHAR UNIQUE NOT NULL,
    telefono VARCHAR(20),
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id) ON DELETE CASCADE
);

-- Profesor
CREATE TABLE Profesor (
    id SERIAL PRIMARY KEY,
    usuario_id VARCHAR UNIQUE NOT NULL,
    especialidad VARCHAR(100),
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id) ON DELETE CASCADE
);

-- Alumno
CREATE TABLE Alumno (
    id SERIAL PRIMARY KEY,
    usuario_id VARCHAR UNIQUE NOT NULL,
    curp VARCHAR(18) UNIQUE NOT NULL,
    tutor_id VARCHAR,
    grado INT,
    nivel VARCHAR(20) CHECK (nivel IN ('Primaria', 'Secundaria')),
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (tutor_id) REFERENCES Tutor(id) ON DELETE SET NULL
);

-- Ciclo Escolar
CREATE TABLE CicloEscolar (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL
);

-- Grupo
CREATE TABLE Grupo (
    id SERIAL PRIMARY KEY,
    nivel VARCHAR(20) CHECK (nivel IN ('Primaria', 'Secundaria')),
    grado SMALLINT CHECK (grado BETWEEN 1 AND 6),
    letra CHAR(1) CHECK (letra BETWEEN 'A' AND 'G'),
    ciclo_escolar_id INT NOT NULL,
    FOREIGN KEY (ciclo_escolar_id) REFERENCES CicloEscolar(id) ON DELETE CASCADE
);

-- Materia
CREATE TABLE Materia (
    id VARCHAR(6) PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    nivel VARCHAR(10) CHECK (nivel IN ('Primaria', 'Secundaria')),
    grado SMALLINT CHECK (grado BETWEEN 1 AND 6),
    especialidad VARCHAR(25) NOT NULL
);

-- Profesor - Grupo - Materia
CREATE TABLE profesor_grupo_materia (
    profesor_id INT,
    grupo_id INT,
    materia_id VARCHAR(6),
    PRIMARY KEY (profesor_id, grupo_id, materia_id),
    FOREIGN KEY (profesor_id) REFERENCES Profesor(id) ON DELETE CASCADE,
    FOREIGN KEY (grupo_id) REFERENCES Grupo(id) ON DELETE CASCADE,
    FOREIGN KEY (materia_id) REFERENCES Materia(id) ON DELETE CASCADE
);

-- Periodo
CREATE TABLE Periodo (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(10) CHECK (nombre IN ('Primer', 'Segundo', 'Tercer')) NOT NULL,
    orden SMALLINT CHECK (orden BETWEEN 1 AND 3),
    ciclo_escolar_id INT NOT NULL,
    FOREIGN KEY (ciclo_escolar_id) REFERENCES CicloEscolar(id)
);

-- Calificación por Materia
CREATE TABLE CalificacionMateria (
    alumno_id INT,
    materia_id VARCHAR(6),
    periodo_id INT,
    ciclo_escolar_id INT,
    calificacion NUMERIC(4,2) CHECK (calificacion BETWEEN 0 AND 10),
    observaciones TEXT,
    PRIMARY KEY (alumno_id, materia_id, periodo_id, ciclo_escolar_id),
    FOREIGN KEY (alumno_id) REFERENCES Alumno(id) ON DELETE CASCADE,
    FOREIGN KEY (materia_id) REFERENCES Materia(id) ON DELETE CASCADE,
    FOREIGN KEY (periodo_id) REFERENCES Periodo(id) ON DELETE CASCADE,
    FOREIGN KEY (ciclo_escolar_id) REFERENCES CicloEscolar(id) ON DELETE CASCADE
);

-- Boleta
CREATE TABLE Boleta (
    alumno_id INT,
    ciclo_escolar_id INT,
    calif_final_general NUMERIC(5,2),
    observaciones TEXT,
    PRIMARY KEY (alumno_id, ciclo_escolar_id),
    FOREIGN KEY (alumno_id) REFERENCES Alumno(id),
    FOREIGN KEY (ciclo_escolar_id) REFERENCES CicloEscolar(id)
);

-- DATOS INICIALES
-- CREAR SECUENCIAS PARA ID
CREATE SEQUENCE IF NOT EXISTS tutor_id_seq START 1;
CREATE SEQUENCE IF NOT EXISTS profesor_id_seq START 1;
CREATE SEQUENCE IF NOT EXISTS alumno_id_seq START 1;
-- Insertar materias de Primaria (1° a 6°)
INSERT INTO Materia (id, nombre, nivel, grado, especialidad) VALUES
('ESP1', 'Español I', 'Primaria', 1, 'Español'),
('MAT1', 'Matemáticas I', 'Primaria', 1, 'Matemáticas'),
('ENS1', 'Exploración de la Naturaleza y la Sociedad I', 'Primaria', 1, 'Ciencias'),
('FCE1', 'Formación Cívica y Ética I', 'Primaria', 1, 'Formación Cívica y Ética'),
('ART1', 'Educación Artística I', 'Primaria', 1, 'Artes'),
('EDU1', 'Educación Física I', 'Primaria', 1, 'Educación Física'),
('ING1', 'Inglés I', 'Primaria', 1, 'Inglés'),

('ESP2', 'Español II', 'Primaria', 2, 'Español'),
('MAT2', 'Matemáticas II', 'Primaria', 2, 'Matemáticas'),
('ENS2', 'Exploración de la Naturaleza y la Sociedad II', 'Primaria', 2, 'Ciencias'),
('FCE2', 'Formación Cívica y Ética II', 'Primaria', 2, 'Formación Cívica y Ética'),
('ART2', 'Educación Artística II', 'Primaria', 2, 'Artes'),
('EDU2', 'Educación Física II', 'Primaria', 2, 'Educación Física'),
('ING2', 'Inglés II', 'Primaria', 2, 'Inglés'),

('ESP3', 'Español III', 'Primaria', 3, 'Español'),
('MAT3', 'Matemáticas III', 'Primaria', 3, 'Matemáticas'),
('CNA1', 'Ciencias Naturales I', 'Primaria', 3, 'Ciencias'),
('EDV1', 'La Entidad donde Vivo', 'Primaria', 3, 'Geografía'),
('FCE3', 'Formación Cívica y Ética III', 'Primaria', 3, 'Formación Cívica y Ética'),
('ART3', 'Educación Artística III', 'Primaria', 3, 'Artes'),
('EDU3', 'Educación Física III', 'Primaria', 3, 'Educación Física'),
('ING3', 'Inglés III', 'Primaria', 3, 'Inglés'),

('ESP4', 'Español IV', 'Primaria', 4, 'Español'),
('MAT4', 'Matemáticas IV', 'Primaria', 4, 'Matemáticas'),
('CNA2', 'Ciencias Naturales II', 'Primaria', 4, 'Ciencias'),
('GEO1', 'Geografía I', 'Primaria', 4, 'Geografía'),
('HIS1', 'Historia I', 'Primaria', 4, 'Historia'),
('FCE4', 'Formación Cívica y Ética IV', 'Primaria', 4, 'Formación Cívica y Ética'),
('ART4', 'Educación Artística IV', 'Primaria', 4, 'Artes'),
('EDU4', 'Educación Física IV', 'Primaria', 4, 'Educación Física'),
('ING4', 'Inglés IV', 'Primaria', 4, 'Inglés'),

('ESP5', 'Español V', 'Primaria', 5, 'Español'),
('MAT5', 'Matemáticas V', 'Primaria', 5, 'Matemáticas'),
('CNA3', 'Ciencias Naturales III', 'Primaria', 5, 'Ciencias'),
('GEO2', 'Geografía II', 'Primaria', 5, 'Geografía'),
('HIS2', 'Historia II', 'Primaria', 5, 'Historia'),
('FCE5', 'Formación Cívica y Ética V', 'Primaria', 5, 'Formación Cívica y Ética'),
('ART5', 'Educación Artística V', 'Primaria', 5, 'Artes'),
('EDU5', 'Educación Física V', 'Primaria', 5, 'Educación Física'),
('ING5', 'Inglés V', 'Primaria', 5, 'Inglés'),

('ESP6', 'Español VI', 'Primaria', 6, 'Español'),
('MAT6', 'Matemáticas VI', 'Primaria', 6, 'Matemáticas'),
('CNA4', 'Ciencias Naturales IV', 'Primaria', 6, 'Ciencias'),
('GEO3', 'Geografía III', 'Primaria', 6, 'Geografía'),
('HIS3', 'Historia III', 'Primaria', 6, 'Historia'),
('FCE6', 'Formación Cívica y Ética VI', 'Primaria', 6, 'Formación Cívica y Ética'),
('ART6', 'Educación Artística VI', 'Primaria', 6, 'Artes'),
('EDU6', 'Educación Física VI', 'Primaria', 6, 'Educación Física'),
('ING6', 'Inglés VI', 'Primaria', 6, 'Inglés');

-- Materias Secundaria
INSERT INTO Materia (id, nombre, nivel, grado, especialidad) VALUES
('ESP7', 'Español I', 'Secundaria', 1, 'Español'),
('MAT7', 'Matemáticas I', 'Secundaria', 1, 'Matemáticas'),
('CIE1', 'Biología', 'Secundaria', 1, 'Ciencias'),
('GEO4', 'Geografía de México y del Mundo', 'Secundaria', 1, 'Geografía'),
('EDF7', 'Educación Física I', 'Secundaria', 1, 'Educación Física'),
('ART7', 'Artes I', 'Secundaria', 1, 'Artes'),
('TEC1', 'Tecnología I', 'Secundaria', 1, 'Tecnología'),
('ING7', 'Inglés I', 'Secundaria', 1, 'Inglés'),

('ESP8', 'Español II', 'Secundaria', 2, 'Español'),
('MAT8', 'Matemáticas II', 'Secundaria', 2, 'Matemáticas'),
('CIE2', 'Física', 'Secundaria', 2, 'Ciencias'),
('HIS4', 'Historia I', 'Secundaria', 2, 'Historia'),
('FCE7', 'Formación Cívica y Ética I', 'Secundaria', 2, 'Formación Cívica y Ética'),
('EDF8', 'Educación Física II', 'Secundaria', 2, 'Educación Física'),
('ART8', 'Artes II', 'Secundaria', 2, 'Artes'),
('TEC2', 'Tecnología II', 'Secundaria', 2, 'Tecnología'),
('ING8', 'Inglés II', 'Secundaria', 2, 'Inglés'),

('ESP9', 'Español III', 'Secundaria', 3, 'Español'),
('MAT9', 'Matemáticas III', 'Secundaria', 3, 'Matemáticas'),
('CIE3', 'Química', 'Secundaria', 3, 'Ciencias'),
('HIS5', 'Historia II', 'Secundaria', 3, 'Historia'),
('FCE8', 'Formación Cívica y Ética II', 'Secundaria', 3, 'Formación Cívica y Ética'),
('EDF9', 'Educación Física III', 'Secundaria', 3, 'Educación Física'),
('ART9', 'Artes III', 'Secundaria', 3, 'Artes'),
('TEC3', 'Tecnología III', 'Secundaria', 3, 'Tecnología'),
('ING9', 'Inglés III', 'Secundaria', 3, 'Inglés');

-- FUNCIONES DE REGISTRO DE USUARIOS Y ROLES
-- Registrar administrador
CREATE OR REPLACE FUNCTION registrar_administrador(
    p_nombre VARCHAR,
    p_apellido_paterno VARCHAR,
    p_apellido_materno VARCHAR
) RETURNS VARCHAR AS $$
DECLARE
    v_usuario_id INTEGER;
    correo_generado VARCHAR;
    id_generado VARCHAR;
BEGIN
    SELECT nextval('usuario_id_seq') INTO v_usuario_id;

    id_generado := 'AD' || LPAD(v_usuario_id::TEXT, 3, '0');
    correo_generado := LOWER(p_apellido_paterno) || '.' || LOWER(id_generado) || '@cal.com';

    INSERT INTO Usuario(id, nombre, apellido_paterno, apellido_materno, correo, contrasena, rol)
    VALUES (id_generado, p_nombre, p_apellido_paterno, p_apellido_materno, correo_generado, '', 'administrador');
    
    RETURN id_generado;
END;
$$ LANGUAGE plpgsql;

-- Registrar tutor
CREATE OR REPLACE FUNCTION registrar_tutor(
    p_nombre VARCHAR,
    p_apellido_paterno VARCHAR,
    p_apellido_materno VARCHAR,
    p_correo VARCHAR,
    p_telefono VARCHAR
) RETURNS VARCHAR AS $$
DECLARE
    v_usuario_id INTEGER;
    v_tutor_id INTEGER;
    correo_generado VARCHAR;
    id_generado VARCHAR;
BEGIN
    SELECT nextval('usuario_id_seq') INTO v_usuario_id;
    id_generado := 'TU' || LPAD(v_usuario_id::TEXT, 3, '0');
    correo_generado := LOWER(p_apellido_paterno) || '.' || LOWER(id_generado) || '@cal.com';
    
    INSERT INTO Usuario(id, nombre, apellido_paterno, apellido_materno, correo, contrasena, rol)
    VALUES (id_generado, p_nombre, p_apellido_paterno, p_apellido_materno, correo_generado, '', 'tutor');
    INSERT INTO Tutor(usuario_id, telefono) VALUES (id_generado, p_telefono)
    RETURNING id INTO v_tutor_id;
    RETURN id_generado;
END;
$$ LANGUAGE plpgsql;

-- Registrar profesor
CREATE OR REPLACE FUNCTION registrar_profesor(
    p_nombre VARCHAR,
    p_apellido_paterno VARCHAR,
    p_apellido_materno VARCHAR,
    p_correo VARCHAR,
    p_especialidad VARCHAR
) RETURNS VARCHAR AS $$
DECLARE
    v_usuario_id INTEGER;
    v_profesor_id INTEGER;
    correo_generado VARCHAR;
    id_generado VARCHAR;
BEGIN
    SELECT nextval('usuario_id_seq') INTO v_usuario_id;
    id_generado := 'PR' || LPAD(v_usuario_id::TEXT, 3, '0');
    correo_generado := LOWER(p_apellido_paterno) || '.' || LOWER(id_generado) || '@cal.com';
    INSERT INTO Usuario(id, nombre, apellido_paterno, apellido_materno, correo, contrasena, rol)
    VALUES (id_generado, p_nombre, p_apellido_paterno, p_apellido_materno, correo_generado, '', 'profesor');
    INSERT INTO Profesor(usuario_id, especialidad) VALUES (id_generado, p_especialidad)
    RETURNING id INTO v_profesor_id;
    RETURN id_generado;
END;
$$ LANGUAGE plpgsql;

-- Registrar alumno
CREATE OR REPLACE FUNCTION registrar_alumno(
    p_nombre VARCHAR,
    p_apellido_paterno VARCHAR,
    p_apellido_materno VARCHAR,
    p_correo VARCHAR,
    p_curp VARCHAR,
    p_grado INT,
    p_nivel VARCHAR,
    p_tutor_id VARCHAR DEFAULT NULL
) RETURNS VARCHAR AS $$
DECLARE
    v_usuario_id INTEGER;
    v_alumno_id INTEGER;
    correo_generado VARCHAR;
    id_generado VARCHAR;
    v_tutor_id VARCHAR;
BEGIN
    SELECT nextval('usuario_id_seq') INTO v_usuario_id;
    id_generado := 'AL' || LPAD(v_usuario_id::TEXT, 3, '0');
    correo_generado := LOWER(p_apellido_paterno) || '.' || LOWER(id_generado) || '@cal.com';

    v_tutor_id := p_tutor_id;

    INSERT INTO Usuario(id, nombre, apellido_paterno, apellido_materno, correo, contrasena, rol)
    VALUES (id_generado, p_nombre, p_apellido_paterno, p_apellido_materno, correo_generado, '', 'alumno');

    INSERT INTO Alumno(usuario_id, curp, tutor_id, grado, nivel)
    VALUES (id_generado, p_curp, v_tutor_id, p_grado, p_nivel)
    RETURNING id INTO v_alumno_id;

    RETURN id_generado;
END;
$$ LANGUAGE plpgsql;

-- ALUMNO_GRUPO
CREATE TABLE IF NOT EXISTS Alumno_Grupo (
    alumno_id INT NOT NULL,
    grupo_id INT NOT NULL,
    PRIMARY KEY (alumno_id, grupo_id),
    FOREIGN KEY (alumno_id) REFERENCES Alumno(id) ON DELETE CASCADE,
    FOREIGN KEY (grupo_id) REFERENCES Grupo(id) ON DELETE CASCADE
);

-- PROCEDIMIENTO PARA ASIGNAR ALUMNO A GRUPO
CREATE OR REPLACE PROCEDURE asignar_alumno_a_grupo(
    p_alumno_id INT,
    p_grupo_id INT
)
LANGUAGE plpgsql
AS $$
BEGIN
    INSERT INTO Alumno_Grupo(alumno_id, grupo_id)
    VALUES (p_alumno_id, p_grupo_id)
    ON CONFLICT DO NOTHING;
END;
$$;

-- PROCEDIMIENTO PARA PROMOVER ALUMNOS
CREATE OR REPLACE PROCEDURE promover_alumnos(
    p_ciclo_actual INT,
    p_ciclo_nuevo INT
)
LANGUAGE plpgsql
AS $$
DECLARE
    rec RECORD;
    v_nuevo_grado INT;
    v_nuevo_grupo_id INT;
BEGIN
    FOR rec IN
        SELECT ag.alumno_id, g.nivel, g.grado, g.letra
        FROM Alumno_Grupo ag
        JOIN Grupo g ON ag.grupo_id = g.id
        WHERE g.ciclo_escolar_id = p_ciclo_actual
    LOOP
        v_nuevo_grado := rec.grado + 1;
        -- Buscar si existe grupo con mismo nivel, nuevo grado y letra en el nuevo ciclo
        SELECT id INTO v_nuevo_grupo_id
        FROM Grupo
        WHERE nivel = rec.nivel AND grado = v_nuevo_grado AND letra = rec.letra AND ciclo_escolar_id = p_ciclo_nuevo
        LIMIT 1;
        IF v_nuevo_grupo_id IS NOT NULL THEN
            INSERT INTO Alumno_Grupo(alumno_id, grupo_id)
            VALUES (rec.alumno_id, v_nuevo_grupo_id)
            ON CONFLICT DO NOTHING;
        END IF;
    END LOOP;
END;
$$;

-- CALIFICACIONES DE TAREAS 
CREATE TABLE CalificacionTarea (
    id SERIAL PRIMARY KEY,
    alumno_id INT NOT NULL,
    materia_id VARCHAR(6) NOT NULL,
    grupo_id INT NOT NULL,
    ciclo_escolar_id INT NOT NULL,
    periodo_id INT NOT NULL,
    calificacion NUMERIC(4,2) CHECK (calificacion BETWEEN 0 AND 10),
    fecha DATE NOT NULL,
    descripcion TEXT,
    FOREIGN KEY (alumno_id) REFERENCES Alumno(id) ON DELETE CASCADE,
    FOREIGN KEY (materia_id) REFERENCES Materia(id) ON DELETE CASCADE,
    FOREIGN KEY (grupo_id) REFERENCES Grupo(id) ON DELETE CASCADE,
    FOREIGN KEY (ciclo_escolar_id) REFERENCES CicloEscolar(id) ON DELETE CASCADE,
    FOREIGN KEY (periodo_id) REFERENCES Periodo(id) ON DELETE CASCADE
);

-- Función para registrar calificación de tarea 
CREATE OR REPLACE FUNCTION registrar_calificacion_tarea(
    p_alumno_id INTEGER,
    p_materia_id VARCHAR,
    p_grupo_id INTEGER,
    p_ciclo_escolar_id INTEGER,
    p_periodo_id INTEGER,
    p_calificacion NUMERIC,
    p_fecha DATE,
    p_descripcion TEXT
) RETURNS VOID AS $$
BEGIN
    INSERT INTO CalificacionTarea(alumno_id, materia_id, grupo_id, ciclo_escolar_id, periodo_id, calificacion, fecha, descripcion)
    VALUES (p_alumno_id, p_materia_id, p_grupo_id, p_ciclo_escolar_id, p_periodo_id, p_calificacion, p_fecha, p_descripcion);
END;
$$ LANGUAGE plpgsql;
