# Casos de Uso - Módulo de Informes de Estudiante

## B6. Ver Historial del Estudiante

**Autor:**  
Karina Ivonne Bazán Rojas  

**Fecha:**  
02/07/2025  

**Descripción:**  
Permite a los actores autorizados consultar el historial académico de un alumno, incluyendo reportes anteriores y observaciones registradas por ciclo y periodo escolar.

**Actores:**  
- Alumno  
- Tutor  
- Administrador  

**Precondiciones:**  
- El actor debe estar autenticado y tener permisos para acceder al historial del alumno.  
- El alumno debe tener registros previos en el sistema (reportes u observaciones).  
- El ciclo escolar debe estar registrado en el sistema.  

**Flujo Normal:**  
1. El actor accede al módulo Informe de Estudiante.  
2. Selecciona el alumno a consultar.  
3. El sistema recupera el historial del alumno y muestra la información organizada cronológicamente.  

**Flujo Alternativo:**  
- Si el alumno no tiene historial registrado, se muestra el mensaje: `"No hay historial disponible para este alumno"`.  

**Poscondiciones:**  
- El actor ha consultado la información histórica del alumno.

---

## B7. Ver Reporte Académico

**Autor:**  
Karina Ivonne Bazán Rojas  

**Fecha:**  
02/07/2025  

**Descripción:**  
Permite a los actores autorizados consultar el reporte académico generado al final de un periodo escolar. Este reporte incluye el promedio general del alumno y observaciones relevantes de desempeño, conducta o necesidades de seguimiento.

**Actores:**  
- Alumno  
- Tutor  
- Administrador  
- Profesor (del periodo actual)  

**Precondiciones:**  
- Debe existir un informe académico previamente generado para el alumno.  
- El actor debe estar autorizado para visualizar reportes del alumno.  
- El alumno debe estar inscrito en un grupo con reporte disponible.  

**Flujo Normal:**  
1. El actor accede al módulo Informe de Estudiante.  
2. Selecciona al alumno y el periodo correspondiente.  
3. El sistema recupera el reporte académico (promedio general y observaciones).  
4. Se visualiza el reporte completo en pantalla con opción a descarga o impresión.  

**Flujo Alternativo:**  
- Si el informe no ha sido generado aún, el sistema muestra: `"El reporte académico aún no está disponible"`.  

**Poscondiciones:**  
- El actor ha visualizado el reporte académico.

---

## B8. Agregar Observación

**Autor:**  
Karina Ivonne Bazán Rojas  

**Fecha:**  
02/07/2025  

**Descripción:**  
Permite a los actores autorizados registrar observaciones académicas sobre un alumno.

**Actores:**  
- Profesor  

**Precondiciones:**  
- El actor debe estar autenticado con permisos para registrar observaciones.  
- El alumno debe estar inscrito en el ciclo escolar actual.  
- Debe seleccionarse un periodo activo para la observación.  

**Flujo Normal:**  
1. El actor accede al módulo de Informe de Estudiante.  
2. Selecciona al alumno correspondiente.  
3. Ingresa la observación o retroalimentación.  
4. El sistema valida el campo y guarda la observación con sello de fecha y autor.  
5. Se muestra confirmación del registro.  

**Flujo Alternativo:**  
- Si el campo de observación está vacío o excede el límite permitido, se muestra un mensaje de error.  

**Poscondiciones:**  
- La observación queda registrada y asociada al alumno y periodo correspondiente.  
- Puede ser consultada posteriormente en el historial o integrarse en el reporte académico.

---