# Casos de Uso - Módulo de Calificaciones

## M1. Asignar Calificación
**Autora:**  
Karina Ivonne Bazán Rojas  

**Fecha:**  
02/07/2025  

**Descripción:**  
Permite al profesor registrar en el sistema las calificaciones finales del alumno para un curso correspondiente a un periodo escolar.

**Actores:**  
- Profesor

**Precondiciones:**  
- El profesor debe tener asignado a un grupo y materia.  
- El periodo escolar debe estar activo para la captura de calificaciones.  
- El alumno debe estar inscrito en el grupo y materia correspondiente.  
- Las calificaciones parciales (tareas) ya deben estar ingresadas (para promediar).  

**Flujo Normal:**  
1. El profesor accede al módulo de Calificaciones.  
2. Selecciona el grupo, la materia y el periodo activo.  
3. El sistema lista a los alumnos inscritos.  
4. El profesor ingresa la calificación final del curso.  
5. El sistema valida el rango de la calificación.  
6. El sistema registra la calificación en la base de datos.  

**Flujo Alternativo:**  
- Si el profesor ingresa un valor fuera del rango válido, el sistema muestra: `"La calificación debe estar entre n-m"`.  
- No permite registrar calificaciones fuera del periodo de captura.  

**Poscondiciones:**  
- La calificación queda registrada formalmente para el alumno, materia y periodo.

---

## B1. Visualización de Calificación

**Autor:**  
Karina Ivonne Bazán Rojas  

**Fecha:**  
02/07/2025  

**Descripción:**  
Permite a los actores visualizar sus calificaciones correspondientes a sus roles.

**Actores:**  
- Administrador  
- Profesor  
- Alumno  
- Tutor  

**Precondiciones:**  
- El profesor debe tener asignado al menos un grupo.  
- El alumno debe pertenecer a un grupo con calificaciones registradas.  
- El tutor debe estar vinculado al alumno.  
- Las calificaciones deben haber sido previamente ingresadas en el sistema.  

**Flujo Normal:**  
1. El actor accede al módulo de Calificaciones.  
2. El sistema solicita la selección del grupo o alumno (según el perfil del actor).  
3. El sistema obtiene las calificaciones registradas.  
4. Se presentan las calificaciones al actor.  

**Flujo Alternativo:**  
- Si no hay calificaciones registradas, se muestra un mensaje: `"No hay calificaciones disponibles"`.  

**Poscondiciones:**  
- El actor ha visualizado la información académica disponible; no se realiza ningún cambio en el sistema.

---

## B2. Modificación de Calificación

**Autor:**  
Karina Ivonne Bazán Rojas  

**Fecha:**  
02/07/2025  

**Descripción:**  
Permite al profesor modificar las calificaciones previamente registradas de los alumnos, pertenecientes a sus grupos asignados.

**Actores:**  
- Profesor  

**Precondiciones:**  
- Deben existir calificaciones previamente registradas para los alumnos del grupo.  
- El alumno debe pertenecer al grupo del proceso.  

**Flujo Normal:**  
1. El profesor accede al módulo de Calificaciones.  
2. Selecciona el grupo y posteriormente el alumno cuya calificación desea modificar.  
3. El sistema muestra las calificaciones actuales.  
4. El profesor realiza los cambios.  
5. El sistema solicita confirmación de los cambios.  
6. Se valida la nueva calificación.  
7. El sistema guarda las modificaciones y muestra un mensaje de éxito.  

**Flujo Alternativo:**  
- Si no hay calificaciones previas, se muestra el mensaje: `"No hay calificación existente para modificar"`.  
- Si el profesor intenta modificar una calificación de un grupo que no le pertenece, el sistema niega el acceso.  
- Si se cancela la modificación antes de confirmar los datos, no se alteran.  

**Poscondiciones:**  
- Las calificaciones del alumno son actualizadas en el sistema.

---