# Casos de Uso - Módulo de Grupos

## M2. Asignar Grupo

**Autor:**  
Karina Ivonne Bazán Rojas  

**Fecha:**  
02/07/2025  

**Descripción:**  
Permite al administrador asignar a los alumnos y profesores a grupos.

**Actores:**  
- Administrador

**Precondiciones:**  
- El administrador debe estar autenticado en el sistema.  
- Deben existir alumnos, profesores y grupos creados previamente.  
- El ciclo escolar y periodo deben estar activos para asignaciones.  

**Flujo Normal:**  
1. El administrador accede al módulo de Asignación de Grupo.  
2. Selecciona el grupo al cual asignar alumnos y profesores.  
3. En una lista desplegable se elige el nivel, grado y grupo.  
4. El sistema muestra las listas disponibles de alumnos y profesores.  
5. El administrador asigna a los alumnos y al profesor al grupo.  
6. El sistema registra las asignaciones y da un mensaje de confirmación.  

**Flujo Alternativo:**  
- Si el grupo no existe o no está activo, el sistema muestra un mensaje de error.  
- Si un alumno o profesor ya está asignado a otro grupo, se notifica y se solicita confirmación o rechazo.  

**Poscondiciones:**  
- Los alumnos y profesores quedan asignados al grupo seleccionado.

---

## B3. Visualizar Grupo

**Autor:**  
Karina Ivonne Bazán Rojas  

**Fecha:**  
02/07/2025  

**Descripción:**  
Permite a los actores autorizados consultar los grupos existentes, junto con la información relacionada (alumnos, nivel, grado, profesor).

**Actores:**  
- Administrador  
- Profesor  

**Precondiciones:**  
- El actor debe estar autenticado en el sistema.  
- Deben existir grupos previamente registrados.  
- El profesor puede visualizar únicamente los grupos a los que está asignado.  

**Flujo Normal:**  
1. El actor accede al módulo de Asignación de Grupo.  
2. Selecciona la opción de “Visualizar grupos”.  
3. El sistema muestra la lista de grupos con información básica: nivel, grado, letra, profesor.  

**Flujo Alternativo:**  
- Si no hay grupos registrados, el sistema muestra el mensaje: `"No existen grupos disponibles"`.  

**Poscondiciones:**  
- El actor ha consultado la información de grupos. No se realiza ninguna modificación.

---

## B4. Visualización de Pertenencia de Grupo

**Autor:**  
Karina Ivonne Bazán Rojas  

**Fecha:**  
02/07/2025  

**Descripción:**  
Permite al alumno y a su tutor consultar a qué grupo está asignado el alumno, incluyendo el nivel, grado, letra y profesor responsable.

**Actores:**  
- Alumno  
- Tutor  

**Precondiciones:**  
- El alumno debe estar registrado en el sistema y asignado a un grupo.  
- El tutor debe estar vinculado al alumno.  
- El ciclo escolar debe estar activo.  

**Flujo Normal:**  
1. El actor accede al sistema.  
2. Selecciona la opción “Visualización de pertenencia”.  
3. El sistema recupera los datos del grupo asignado al alumno y muestra el nivel, grado, letra y nombre del profesor a cargo.  

**Flujo Alternativo:**  
- Si el alumno no está asignado a ningún grupo, el sistema muestra el mensaje: `"El alumno aún no ha sido asignado a un grupo"`.  

**Poscondiciones:**  
- El actor ha visualizado correctamente la información del grupo al que pertenece el alumno.

---

## B5. Modificar Grupo

**Autor:**  
Karina Ivonne Bazán Rojas  

**Fecha:**  
02/07/2025  

**Descripción:**  
Permite al administrador realizar cambios en la asignación de alumnos o profesores, dentro de un grupo ya existente.

**Actores:**  
- Administrador  

**Precondiciones:**  
- El administrador debe estar autenticado en el sistema.  
- Debe existir al menos un grupo previamente registrado.  
- Deben existir alumnos y profesores disponibles para reasignación.  
- El ciclo escolar debe estar activo.  

**Flujo Normal:**  
1. El administrador accede al módulo de Asignación de Grupo.  
2. Selecciona un grupo existente para modificar.  
3. El sistema muestra los datos actuales del grupo.  
4. El administrador edita la información necesaria:  
   - Reasignar profesor.  
   - Agregar o remover alumnos.  
5. El sistema confirma los cambios y guarda la modificación con un mensaje de éxito.  

**Flujo Alternativo:**  
- Si el grupo seleccionado no existe, se muestra: `"Grupo no encontrado"`.  
- Si se intenta remover a todos los alumnos o dejar el grupo sin profesor, se solicita confirmación adicional.  
- Si hay conflictos de horario o carga docente, el sistema muestra advertencias.  

**Poscondiciones:**  
- La información del grupo queda actualizada en el sistema y disponible para módulos relacionados.

---