-- Secuencia: tutores (si no existe)
DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_class WHERE relkind = 'S' AND relname = 'tutor_id_seq') THEN
        CREATE SEQUENCE tutor_id_seq START WITH 1;
    END IF;
END$$;
-- Función: Registrar Tutor
CREATE OR REPLACE FUNCTION registrar_tutor(
    p_nombre VARCHAR,
    p_apellido_p VARCHAR,
    p_apellido_m VARCHAR,
    p_correo VARCHAR,
    p_telefono VARCHAR
)
RETURNS VARCHAR(6) AS $$
DECLARE
    secuencia_val INT;
    nuevo_id_tutor VARCHAR(6);
BEGIN
    -- Validar que correo y teléfono NO sean nulos
    IF p_correo IS NULL OR TRIM(p_correo) = '' THEN
        RAISE EXCEPTION 'Campo obligatorio.';
    END IF;
    IF p_telefono IS NULL OR TRIM(p_telefono) = '' THEN
        RAISE EXCEPTION 'Campo obligatorio.';
    END IF;

    -- Generar nuevo ID
    SELECT nextval('tutor_id_seq') INTO secuencia_val;
    nuevo_id_tutor := 'TU' || LPAD(secuencia_val::TEXT, 3, '0');

    -- Insertar en Usuario
    INSERT INTO Usuario (id_usuario, rol)
    VALUES (nuevo_id_tutor, 'tutor');

    -- Insertar en Tutor
    INSERT INTO Tutor (
        id_tutor, nombre, apellido_p, apellido_m, correo, telefono, id_usuario
    ) VALUES (
        nuevo_id_tutor, p_nombre, p_apellido_p, p_apellido_m, p_correo, p_telefono, nuevo_id_tutor
    );

    RETURN nuevo_id_tutor;

EXCEPTION
    WHEN unique_violation THEN
        RAISE EXCEPTION 'El correo % ya está registrado.', p_correo;
END;
$$ LANGUAGE plpgsql;

-- Secuencia: profesores (si no existe)
DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_class WHERE relkind = 'S' AND relname = 'profesor_id_seq') THEN
        CREATE SEQUENCE profesor_id_seq START WITH 1;
    END IF;
END$$;
-- Función: Registrar Profesor
CREATE OR REPLACE FUNCTION registrar_profesor(
    p_nombre VARCHAR,
    p_apellido_p VARCHAR,
    p_apellido_m VARCHAR,
    p_especializacion VARCHAR
)
RETURNS VARCHAR(6) AS $$
DECLARE
    secuencia_val INT;
    nuevo_id_profesor VARCHAR(6);
    correo_generado VARCHAR(50);
BEGIN
    -- Validar especialización NO nula o vacía
    IF p_especializacion IS NULL OR TRIM(p_especializacion) = '' THEN
        RAISE EXCEPTION 'La especialización es obligatoria para profesor.';
    END IF;

    -- Generar nuevo ID
    SELECT nextval('profesor_id_seq') INTO secuencia_val;
    nuevo_id_profesor := 'PR' || LPAD(secuencia_val::TEXT, 3, '0');

    -- Generar correo automático: apellido_p.apellido_m.id@cal.mx
    correo_generado := LOWER(p_apellido_p) || '.' || LOWER(p_apellido_m) || '.' || nuevo_id_profesor || '@cal.mx';

    -- Insertar en Usuario
    INSERT INTO Usuario (id_usuario, rol)
    VALUES (nuevo_id_profesor, 'profesor');

    -- Insertar en Profesor
    INSERT INTO Profesor (
        id_profesor, nombre, apellido_p, apellido_m, correo, especializacion, id_usuario
    ) VALUES (
        nuevo_id_profesor, p_nombre, p_apellido_p, p_apellido_m, correo_generado, p_especializacion, nuevo_id_profesor
    );

    RETURN nuevo_id_profesor;

EXCEPTION
    WHEN unique_violation THEN
        RAISE EXCEPTION 'El correo % ya está registrado.', correo_generado;
END;
$$ LANGUAGE plpgsql;

-- Secuencia: alumnos (si no existe)
DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_class WHERE relkind = 'S' AND relname = 'alumno_id_seq') THEN
        CREATE SEQUENCE alumno_id_seq START WITH 1;
    END IF;
END$$;
-- Función: Registrar Alumno
CREATE OR REPLACE FUNCTION registrar_alumno(
    p_nombre VARCHAR,
    p_apellido_p VARCHAR,
    p_apellido_m VARCHAR,
    p_curp VARCHAR,
    p_id_tutor VARCHAR
)
RETURNS VARCHAR(6) AS $$
DECLARE
    secuencia_val INT;
    nuevo_id_alumno VARCHAR(6);
    correo_generado VARCHAR(50);
BEGIN
    -- Validar existencia del tutor
    IF NOT EXISTS (SELECT 1 FROM Tutor WHERE id_tutor = p_id_tutor) THEN
        RAISE EXCEPTION 'Tutor % no existe.', p_id_tutor;
    END IF;

    -- Generar nuevo ID
    SELECT nextval('alumno_id_seq') INTO secuencia_val;
    nuevo_id_alumno := 'AL' || LPAD(secuencia_val::TEXT, 3, '0');

    -- Generar correo automático
    correo_generado := LOWER(SUBSTRING(p_curp FROM 1 FOR 4)) || '.' || nuevo_id_alumno || '@cal.mx';

    -- Insertar en Usuario
    INSERT INTO Usuario (id_usuario, rol)
    VALUES (nuevo_id_alumno, 'alumno');

    -- Insertar en Alumno
    INSERT INTO Alumno (
        id_alumno, nombre, apellido_p, apellido_m,
        correo, curp, id_usuario, id_tutor
    ) VALUES (
        nuevo_id_alumno, p_nombre, p_apellido_p, p_apellido_m,
        correo_generado, p_curp, nuevo_id_alumno, p_id_tutor
    );

    RETURN nuevo_id_alumno;

EXCEPTION
    WHEN unique_violation THEN
        RAISE EXCEPTION 'El CURP % ya está registrado.', p_curp;
END;
$$ LANGUAGE plpgsql;

-- Función: Registrar Admin
CREATE OR REPLACE FUNCTION registrar_administrador(
    p_nombre VARCHAR,
    p_apellido_p VARCHAR,
    p_apellido_m VARCHAR
)
RETURNS VARCHAR(6) AS $$
DECLARE
    secuencia_val INT;
    nuevo_id_admin VARCHAR(6);
    correo_generado VARCHAR(100);
BEGIN
    -- Crear secuencia si no existe
    PERFORM 1 FROM pg_class WHERE relname = 'admin_id_seq' AND relkind = 'S';
    IF NOT FOUND THEN
        EXECUTE 'CREATE SEQUENCE admin_id_seq START WITH 1';
    END IF;

    SELECT nextval('admin_id_seq') INTO secuencia_val;
    nuevo_id_admin := 'AD' || LPAD(secuencia_val::TEXT, 3, '0');

    -- Correo automático
    correo_generado := LOWER(p_apellido_p || '.' || p_apellido_m || nuevo_id_admin) || '@cal.mx';

    -- Insertar en Usuario
    INSERT INTO Usuario(id_usuario, rol)
    VALUES (nuevo_id_admin, 'administrador');

    -- Insertar en Administrador
    INSERT INTO Administrador(
        id_admin, nombre, apellido_p, apellido_m,
        correo, id_usuario
    ) VALUES (
        nuevo_id_admin, p_nombre, p_apellido_p, p_apellido_m,
        correo_generado, nuevo_id_admin
    );

    RETURN nuevo_id_admin;

EXCEPTION
    WHEN unique_violation THEN
        RAISE EXCEPTION 'El correo generado % ya existe.', correo_generado;
END;
$$ LANGUAGE plpgsql;

-- Función: Preview de Aisgncaión de Grupo
CREATE OR REPLACE FUNCTION preview_asignacion_grupos(
    p_nivel VARCHAR,
    p_grado INT,
    p_num_grupos INT
)
RETURNS TABLE (
    id_alumno VARCHAR(6),
    nombre_completo TEXT,
    letra_grupo CHAR
) AS $$
BEGIN
    RETURN QUERY
    WITH alumnos_filtrados AS (
        SELECT 
            a.id_alumno,
            a.nombre || ' ' || a.apellido_p || ' ' || a.apellido_m AS nombre_completo,
            ROW_NUMBER() OVER (ORDER BY a.apellido_p, a.apellido_m, a.nombre) - 1 AS fila
        FROM Alumno a
        WHERE NOT EXISTS (
            SELECT 1 FROM AsignacionGrupoAlumno ag
            WHERE ag.id_alumno = a.id_alumno
        )
    ),
    asignaciones AS (
        SELECT 
            id_alumno,
            nombre_completo,
            CHR(65 + (fila % p_num_grupos))::CHAR AS letra_grupo
        FROM alumnos_filtrados
    )
    SELECT * FROM asignaciones;
END;
$$ LANGUAGE plpgsql;

	-- Guardar los datos
CREATE OR REPLACE FUNCTION asignar_grupos_confirmado(
    p_nivel VARCHAR,
    p_grado INT,
    p_num_grupos INT
)
RETURNS VOID AS $$
DECLARE
    letras TEXT[] := ARRAY[]::TEXT[];
    id_grupo_asignado INT;
    id_ciclo_actual INT;
    fila INT := 0;
    alumno_id VARCHAR(6);
    letra CHAR;
    alumno_cursor CURSOR FOR
        SELECT a.id_alumno
        FROM Alumno a
        WHERE NOT EXISTS (
            SELECT 1 FROM AsignacionGrupoAlumno ag
            WHERE ag.id_alumno = a.id_alumno
        )
        ORDER BY a.apellido_p, a.apellido_m, a.nombre;
BEGIN
    -- Obtener ciclo escolar actual
    SELECT id_ciclo INTO id_ciclo_actual
    FROM CicloEscolar
    ORDER BY fecha_inicio DESC
    LIMIT 1;

    -- Crear los grupos y guardar sus IDs
    FOR i IN 0..p_num_grupos - 1 LOOP
        letra := CHR(65 + i);
        INSERT INTO Grupo(nivel, grado, letra, id_ciclo)
        VALUES (p_nivel, p_grado, letra, id_ciclo_actual)
        RETURNING id_grupo INTO id_grupo_asignado;

        letras := array_append(letras, id_grupo_asignado::TEXT);
    END LOOP;

    -- Asignar alumnos a grupos y guardar en AsignacionGrupoAlumno
    OPEN alumno_cursor;
    LOOP
        FETCH alumno_cursor INTO alumno_id;
        EXIT WHEN NOT FOUND;

        id_grupo_asignado := letras[(fila % p_num_grupos) + 1]::INT;

        INSERT INTO AsignacionGrupoAlumno(id_alumno, id_grupo, id_ciclo)
        VALUES (alumno_id, id_grupo_asignado, id_ciclo_actual);

        fila := fila + 1;
    END LOOP;
    CLOSE alumno_cursor;
END;
$$ LANGUAGE plpgsql;

-- Función: Pasar grupo al siguiente nivel
CREATE OR REPLACE FUNCTION promocionar_grupos_al_siguiente_ciclo()
RETURNS VOID AS $$
DECLARE
    ciclo_anterior INT;
    ciclo_actual INT;
    grupo_anterior RECORD;
    nuevo_grado INT;
    nuevo_grupo_id INT;
BEGIN
    -- 1. Obtener ciclo actual y anterior
    SELECT id_ciclo INTO ciclo_actual
    FROM CicloEscolar
    WHERE es_actual = TRUE;

    SELECT id_ciclo INTO ciclo_anterior
    FROM CicloEscolar
    WHERE id_ciclo < ciclo_actual
    ORDER BY fecha_inicio DESC
    LIMIT 1;

    IF ciclo_anterior IS NULL THEN
        RAISE EXCEPTION 'No existe un ciclo anterior al actual.';
    END IF;

    -- 2. Recorrer grupos del ciclo anterior
    FOR grupo_anterior IN
        SELECT * FROM Grupo
        WHERE id_ciclo = ciclo_anterior
    LOOP
        -- 3. Verificar si aún no es último grado
        IF (
            (grupo_anterior.nivel = 'Primaria' AND grupo_anterior.grado < 6) OR
            (grupo_anterior.nivel = 'Secundaria' AND grupo_anterior.grado < 3)
        ) THEN
            nuevo_grado := grupo_anterior.grado + 1;

            -- 4. Crear nuevo grupo en el ciclo actual
            INSERT INTO Grupo(nivel, grado, letra, id_ciclo)
            VALUES (grupo_anterior.nivel, nuevo_grado, grupo_anterior.letra, ciclo_actual)
            RETURNING id_grupo INTO nuevo_grupo_id;

            -- 5. Asignar alumnos del grupo anterior al nuevo grupo
            INSERT INTO AsignacionGrupoAlumno(id_alumno, id_grupo, id_ciclo)
            SELECT ag.id_alumno, nuevo_grupo_id, ciclo_actual
            FROM AsignacionGrupoAlumno ag
            WHERE ag.id_grupo = grupo_anterior.id_grupo
              AND ag.id_ciclo = ciclo_anterior
              AND NOT EXISTS (
                  SELECT 1 FROM AsignacionGrupoAlumno ag2
                  WHERE ag2.id_alumno = ag.id_alumno AND ag2.id_ciclo = ciclo_actual
              );
        END IF;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- Función: Egresados
CREATE OR REPLACE FUNCTION obtener_egresados_para_promocion()
RETURNS TABLE (
    id_alumno VARCHAR(6),
    nombre_completo TEXT,
    curp VARCHAR(18),
    id_grupo_anterior INT
) AS $$
BEGIN
    RETURN QUERY
    SELECT
        a.id_alumno,
        a.nombre || ' ' || a.apellido_p || ' ' || a.apellido_m AS nombre_completo,
        a.curp,
        aga.id_grupo
    FROM Alumno a
    INNER JOIN AsignacionGrupoAlumno aga ON a.id_alumno = aga.id_alumno
    INNER JOIN Grupo g ON aga.id_grupo = g.id_grupo
    INNER JOIN CicloEscolar c ON aga.id_ciclo = c.id_ciclo
    WHERE g.nivel = 'Primaria'
      AND g.grado = 6
      AND c.id_ciclo = (SELECT id_ciclo FROM CicloEscolar ORDER BY fecha_inicio DESC LIMIT 1)
      AND NOT EXISTS (
          SELECT 1
          FROM AsignacionGrupoAlumno ag2
          INNER JOIN Grupo g2 ON ag2.id_grupo = g2.id_grupo
          WHERE ag2.id_alumno = a.id_alumno AND g2.nivel = 'Secundaria'
      )
    ORDER BY a.apellido_p, a.apellido_m, a.nombre;
END;
$$ LANGUAGE plpgsql;

-- SP: Asignar grupo a egresados (6°)
CREATE OR REPLACE PROCEDURE asignar_grupos_secundaria_egresados(
    alumnos_a_asignar VARCHAR[],  -- lista de IDs de alumnos seleccionados
    num_grupos INT
)
LANGUAGE plpgsql AS $$
DECLARE
    id_ciclo_actual INT;
    idx INT := 0;
    letra CHAR;
    grupo_id INT;
    grupo_ids INT[] := ARRAY[]::INT[];
    alumno_id VARCHAR(6);
BEGIN
    -- 1. Obtener ciclo escolar actual
    SELECT id_ciclo INTO id_ciclo_actual
    FROM CicloEscolar
    WHERE es_actual = TRUE;

    -- 2. Crear grupos de secundaria
    FOR idx IN 1..num_grupos LOOP
        letra := CHR(64 + idx); -- 65 = A
        INSERT INTO Grupo(nivel, grado, letra, id_ciclo)
        VALUES ('Secundaria', 1, letra, id_ciclo_actual)
        RETURNING id_grupo INTO grupo_id;

        grupo_ids := array_append(grupo_ids, grupo_id);
    END LOOP;

    -- 3. Asignar alumnos ordenados
    idx := 0;
    FOR alumno_id IN SELECT unnest(alumnos_a_asignar) ORDER BY 1 LOOP
        idx := idx + 1;
        INSERT INTO AsignacionGrupoAlumno(id_alumno, id_grupo, id_ciclo)
        VALUES (
            alumno_id,
            grupo_ids[((idx - 1) % num_grupos) + 1],
            id_ciclo_actual
        );
    END LOOP;
END;
$$;

-- SP: Asignar pofesores a grupo por materia (especialización)
	-- Agregar a la tabla Materia la columna especialidad
	ALTER TABLE Materia ADD COLUMN especialidad VARCHAR(50); -- se cambia a varchar (50) de text
	
CREATE OR REPLACE PROCEDURE asignar_profesores_a_materias_por_especialidad()
LANGUAGE plpgsql AS $$
DECLARE
    materia RECORD;
    grupo RECORD;
    profesores TEXT[];
    idx INT := 0;
    profesor_asignado VARCHAR(6);
BEGIN
    -- Recorremos todas las materias con especialidad definida
    FOR materia IN
        SELECT * FROM Materia
        WHERE especialidad IS NOT NULL
    LOOP
        -- Obtener profesores con esa especialidad
        SELECT ARRAY_AGG(p.id_profesor ORDER BY p.id_profesor)
        INTO profesores
        FROM Profesor p
        WHERE p.especialidad = materia.especialidad;

        -- Si no hay profesores disponibles, continuar con la siguiente materia
        IF profesores IS NULL OR array_length(profesores, 1) = 0 THEN
            RAISE NOTICE 'No hay profesores con especialidad % para materia %', materia.especialidad, materia.nombre;
            CONTINUE;
        END IF;

        idx := 0;

        -- Recorrer todos los grupos existentes
        FOR grupo IN
            SELECT * FROM Grupo
        LOOP
            -- Elegir profesor en orden circular
            idx := idx + 1;
            profesor_asignado := profesores[((idx - 1) % array_length(profesores, 1)) + 1];

            -- Insertar si aún no existe asignación
            INSERT INTO ProfesorGrupoMateria(id_profesor, id_grupo, id_materia)
            SELECT profesor_asignado, grupo.id_grupo, materia.id_materia
            WHERE NOT EXISTS (
                SELECT 1 FROM ProfesorGrupoMateria
                WHERE id_profesor = profesor_asignado
                  AND id_grupo = grupo.id_grupo
                  AND id_materia = materia.id_materia
            );

            RAISE NOTICE 'Profesor % asignado a materia % en grupo %°% (%).',
                profesor_asignado, materia.nombre, grupo.grado, grupo.nivel, grupo.letra;
        END LOOP;
    END LOOP;
END;
$$;

-- Modificar tabla Periodo para agregar fechas y estado
ALTER TABLE Periodo
ADD COLUMN fecha_inicio DATE,
ADD COLUMN fecha_fin DATE,
ADD COLUMN estado VARCHAR(10) DEFAULT 'abierto' CHECK (estado IN ('abierto', 'cerrado'));

-- SP: Crear Ciclo
CREATE OR REPLACE PROCEDURE crear_ciclo_escolar(
    p_fecha_inicio DATE,
    p_fecha_fin DATE
)
LANGUAGE plpgsql AS $$
DECLARE
    nombre_ciclo_generado VARCHAR(9);
    id_ciclo_nuevo INT;
    duracion_total INTERVAL;
    duracion_periodo INTERVAL;
    i INT;
    nombres_periodos TEXT[] := ARRAY['Primer', 'Segundo', 'Tercer'];
    fecha_inicio_periodo DATE;
    fecha_fin_periodo DATE;
BEGIN
    -- Validación de fechas
    IF p_fecha_fin <= p_fecha_inicio THEN
        RAISE EXCEPTION 'La fecha de fin debe ser posterior a la fecha de inicio.';
    END IF;

    -- Generar nombre del ciclo escolar
    nombre_ciclo_generado := TO_CHAR(p_fecha_inicio, 'YYYY') || '-' || TO_CHAR(p_fecha_fin, 'YYYY');

    -- Validar si ya existe ese ciclo
    IF EXISTS (SELECT 1 FROM CicloEscolar WHERE nombre_ciclo = nombre_ciclo_generado) THEN
        RAISE EXCEPTION 'El ciclo escolar % ya existe.', nombre_ciclo_generado;
    END IF;

    -- Desactivar cualquier otro ciclo que esté como actual
    UPDATE CicloEscolar SET es_actual = FALSE WHERE es_actual = TRUE;

    -- Insertar nuevo ciclo como actual
    INSERT INTO CicloEscolar(nombre_ciclo, fecha_inicio, fecha_fin, es_actual)
    VALUES (nombre_ciclo_generado, p_fecha_inicio, p_fecha_fin, TRUE)
    RETURNING id_ciclo INTO id_ciclo_nuevo;

    -- Calcular duración por periodo (aproximadamente igual)
    duracion_total := p_fecha_fin - p_fecha_inicio;
    duracion_periodo := duracion_total / 3;

    -- Crear 3 periodos
    FOR i IN 1..3 LOOP
        fecha_inicio_periodo := p_fecha_inicio + duracion_periodo * (i - 1);
        fecha_fin_periodo := CASE
            WHEN i < 3 THEN p_fecha_inicio + duracion_periodo * i - INTERVAL '1 day'
            ELSE p_fecha_fin
        END;

        INSERT INTO Periodo(nombre, orden, id_ciclo, fecha_inicio, fecha_fin, estado)
        VALUES (nombres_periodos[i], i, id_ciclo_nuevo, fecha_inicio_periodo, fecha_fin_periodo, 'abierto');
    END LOOP;

    RAISE NOTICE 'Ciclo escolar % creado con éxito y marcado como actual.', nombre_ciclo_generado;
END;
$$;

-- SP: Calificar tarea 
CREATE OR REPLACE PROCEDURE registrar_calificacion_tarea(
    p_id_tarea INT,
    p_id_alumno VARCHAR(6),
    p_calificacion FLOAT,
    p_observaciones TEXT
)
LANGUAGE plpgsql AS $$
DECLARE
    v_fecha_entrega DATE;
BEGIN
    -- Validar que no exista ya la calificación
    IF EXISTS (
        SELECT 1 FROM CalificacionTarea
        WHERE id_tarea = p_id_tarea AND id_alumno = p_id_alumno
    ) THEN
        RAISE EXCEPTION 'El alumno ya tiene una calificación registrada para esta tarea.';
    END IF;

    -- Verificar que la tarea exista y obtener la fecha de entrega
    SELECT fecha_entrega INTO v_fecha_entrega
    FROM Tarea
    WHERE id_tarea = p_id_tarea;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'La tarea no existe.';
    END IF;

    -- Validar que la fecha de entrega ya haya pasado
    IF CURRENT_DATE < v_fecha_entrega THEN
        RAISE EXCEPTION 'No puedes calificar antes de la fecha de entrega (%).', v_fecha_entrega;
    END IF;

    -- Registrar calificación
    INSERT INTO CalificacionTarea(id_tarea, id_alumno, calificacion, observaciones, fecha_registro)
    VALUES (p_id_tarea, p_id_alumno, p_calificacion, p_observaciones, CURRENT_DATE);
    
    RAISE NOTICE 'Calificación registrada correctamente.';
END;
$$;

-- Función: Promediar tareas
CREATE OR REPLACE FUNCTION calcular_promedio_tareas_alumno_materia_periodo(
    p_id_alumno VARCHAR(6),
    p_id_materia VARCHAR(6),
    p_id_ciclo INT,
    p_id_periodo INT
) RETURNS FLOAT AS $$
DECLARE
    v_promedio FLOAT;
    v_fecha_inicio DATE;
    v_fecha_fin DATE;
BEGIN
    -- Obtener las fechas del periodo
    SELECT fecha_inicio, fecha_fin
    INTO v_fecha_inicio, v_fecha_fin
    FROM Periodo
    WHERE id_periodo = p_id_periodo
      AND id_ciclo = p_id_ciclo;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Periodo no encontrado para el ciclo especificado.';
    END IF;

    -- Calcular promedio de tareas entregadas en el periodo y materia
    SELECT AVG(ct.calificacion)
    INTO v_promedio
    FROM CalificacionTarea ct
    JOIN Tarea t ON ct.id_tarea = t.id_tarea
    JOIN Grupo g ON t.id_grupo = g.id_grupo
    JOIN Inscripcion i ON i.id_alumno = ct.id_alumno AND i.id_grupo = g.id_grupo
    WHERE ct.id_alumno = p_id_alumno
      AND t.id_materia = p_id_materia
      AND g.id_ciclo = p_id_ciclo
      AND t.fecha_entrega BETWEEN v_fecha_inicio AND v_fecha_fin;

    -- Retorna NULL si no hay tareas calificadas (o usa COALESCE para 0)
    RETURN v_promedio;
END;
$$ LANGUAGE plpgsql;

-- Vista para BOLETA 
CREATE OR REPLACE VIEW vista_boleta_alumno AS
SELECT
    cm.id_alumno,
    cm.id_ciclo,
    m.nombre AS materia,
    MAX(CASE WHEN p.orden = 1 THEN cm.calif_final END) AS calif_1p,
    MAX(CASE WHEN p.orden = 2 THEN cm.calif_final END) AS calif_2p,
    MAX(CASE WHEN p.orden = 3 THEN cm.calif_final END) AS calif_3p,
    ROUND(AVG(cm.calif_final)) AS calif_final
FROM CalificacionMateria cm
JOIN Materia m ON m.id_materia = cm.id_materia
JOIN Periodo p ON p.id_periodo = cm.id_periodo
GROUP BY cm.id_alumno, cm.id_ciclo, m.nombre;

-- SP: Crear boletas al iniciar el ciclo
CREATE OR REPLACE PROCEDURE crear_boletas_por_ciclo(
    p_id_ciclo INT
)
LANGUAGE plpgsql AS $$
DECLARE
    r RECORD;
    v_total INT := 0;
BEGIN
    -- Validar que el ciclo exista
    IF NOT EXISTS (SELECT 1 FROM CicloEscolar WHERE id_ciclo = p_id_ciclo) THEN
        RAISE EXCEPTION 'El ciclo escolar con id % no existe.', p_id_ciclo;
    END IF;

    -- Recorrer todos los alumnos inscritos en grupos de ese ciclo
    FOR r IN
        SELECT DISTINCT a.id_alumno
        FROM Alumno a
        JOIN AlumnoGrupo ag ON ag.id_alumno = a.id_alumno
        JOIN Grupo g ON g.id_grupo = ag.id_grupo
        WHERE g.id_ciclo = p_id_ciclo
    LOOP
        -- Crear la boleta solo si no existe ya
        IF NOT EXISTS (
            SELECT 1 FROM Boleta
            WHERE id_alumno = r.id_alumno AND id_ciclo = p_id_ciclo
        ) THEN
            INSERT INTO Boleta(id_alumno, id_ciclo, calif_final_general, observaciones)
            VALUES (r.id_alumno, p_id_ciclo, NULL, NULL);
            v_total := v_total + 1;
        END IF;
    END LOOP;

    RAISE NOTICE 'Se crearon % boletas para el ciclo %.', v_total, p_id_ciclo;
END;
$$;

-- SP: Registrar calificaciones por periodo
CREATE OR REPLACE PROCEDURE registrar_calificacion_materia_periodo(
    p_id_alumno VARCHAR(6),
    p_id_materia VARCHAR(6),
    p_id_ciclo INT,
    p_id_periodo INT,
    p_calificacion INT,
    p_observaciones TEXT
)
LANGUAGE plpgsql AS $$
DECLARE
    v_estado_periodo TEXT;
BEGIN
    -- Validar existencia del periodo y que esté abierto
    SELECT estado
    INTO v_estado_periodo
    FROM Periodo
    WHERE id_periodo = p_id_periodo AND id_ciclo = p_id_ciclo;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'El periodo no existe en el ciclo indicado.';
    END IF;

    IF v_estado_periodo = 'cerrado' THEN
        RAISE EXCEPTION 'No se puede registrar calificación. El periodo ya está cerrado.';
    END IF;

    -- Validar que la boleta exista
    IF NOT EXISTS (
        SELECT 1 FROM Boleta
        WHERE id_alumno = p_id_alumno AND id_ciclo = p_id_ciclo
    ) THEN
        RAISE EXCEPTION 'El alumno no tiene boleta creada para el ciclo %.', p_id_ciclo;
    END IF;

    -- Validar que no exista ya una calificación registrada
    IF EXISTS (
        SELECT 1 FROM CalificacionMateria
        WHERE id_alumno = p_id_alumno AND id_materia = p_id_materia
          AND id_ciclo = p_id_ciclo AND id_periodo = p_id_periodo
    ) THEN
        RAISE EXCEPTION 'Ya existe una calificación registrada para este alumno, materia y periodo.';
    END IF;

    -- Insertar calificación
    INSERT INTO CalificacionMateria (
        id_alumno, id_materia, id_ciclo, id_periodo,
        calif_final, observaciones, fecha_registro
    ) VALUES (
        p_id_alumno, p_id_materia, p_id_ciclo, p_id_periodo,
        p_calificacion, p_observaciones, CURRENT_DATE
    );

    RAISE NOTICE 'Calificación de materia registrada correctamente.';
END;
$$;



















