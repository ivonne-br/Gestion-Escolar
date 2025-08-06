<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/database.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = '/GestionEscolar/public';
$route = str_replace($base, '', $uri);

switch ($route) {
    // Login (autenticación y autorización)
    case '/auth/login':
        require_once '../app/views/auth/login.php';
        break;

    case '/auth/procesar_login':
        require_once '../app/controllers/LoginController.php';
        break;

    // Logout
    case '/logout':
        require_once '../app/controllers/LogoutController.php';
        break;

    // usuarios
    case '/usuarios/actualizar_contrasena':
        require_once '../app/controllers/ActualizarContrasenaController.php';
        break;

    case '/usuarios/form_actualizar_contrasena':
        require_once '../app/views/usuarios/actualizar_contrasena.php';
        break;

    // administradores
    case '/administradores/dashboard':
        require_once '../app/views/administradores/dashboard.php';
        break;

    case '/administradores/formulario':
        require_once '../app/views/administradores/formulario.php';
        break;

    case '/administradores/registrar':
        require_once '../app/controllers/AdministradorController.php';
        break;

     // profesores
    case '/profesores/opciones':
        require_once '../app/views/profesores/opciones.php';
        break;

    case '/profesores/detalle':
        require_once '../app/views/profesores/detalle.php';
        break;

    case '/profesores/index':
        require_once '../app/views/profesores/index.php';
        break;

    case '/profesores/formulario':
        require_once '../app/views/profesores/formulario.php';
        break;

    case '/profesores/editar':
        require_once '../app/views/profesores/editar.php';
        break;        

    case '/profesores/registrar':
        require_once '../app/controllers/ProfesorController.php';
        break;

    case '/profesores/eliminar':
        require_once '../app/controllers/ProfesorController.php';
        break;

    case '/profesores/actualizar':
        require_once '../app/controllers/ProfesorController.php';
        break;

    // Tutores y Alumnos
    case '/alumnos/tutor_alumno':
        require_once '../app/views/alumnos/tutor_alumno.php';
        break;

    // Tutores
    case '/tutores/opciones':
        require_once '../app/views/tutores/opciones.php';
        break;

    case '/tutores/index':
        require_once '../app/views/tutores/index.php';
        break;

    case '/tutores/formulario':
        require_once '../app/views/tutores/formulario.php';
        break;

    case '/tutores/editar':
        require_once '../app/views/tutores/editar.php';
        break;

    case '/tutores/registrar':
        require_once '../app/controllers/TutorController.php';
        break;

    case '/tutores/eliminar':
        require_once '../app/controllers/TutorController.php';
        break;

    case '/tutores/actualizar':
        require_once '../app/controllers/TutorController.php';
        break;


    // Alumnos
    case '/alumnos/opciones':
        require_once '../app/views/alumnos/opciones.php';
        break;

    case '/alumnos/index':
        require_once '../app/views/alumnos/index.php';
        break;

    case '/alumnos/formulario':
        require_once '../app/views/alumnos/formulario.php';
        break;

    case '/alumnos/editar':
        require_once '../app/views/alumnos/editar.php';
        break;

    case '/alumnos/registrar':
        require_once '../app/controllers/AlumnoController.php';
        break;

    case '/alumnos/eliminar':
        require_once '../app/controllers/AlumnoController.php';
        break;

    case '/alumnos/actualizar':
        require_once '../app/controllers/AlumnoController.php';
        break;
    
    // Crear Ciclo Escolar
    case '/grupos/crear_ciclo':
        require_once '../app/views/grupos/crear_ciclo.php';
        break;

    // Grupos
    case '/grupos/opciones':
        require_once '../app/views/grupos/opciones.php';
        break;       

    case '/grupos/index':
        require_once '../app/views/grupos/index.php';
        break;

    case '/grupos/formulario':
        require_once '../app/controllers/GrupoController.php';
        break;

    case '/grupos/preview_asignacion':
        require_once '../app/controllers/GrupoController.php';
        break;
    
    case '/grupos/asignar_final':
        require_once '../app/controllers/GrupoController.php';
        break;

    case '/grupos/editar':
        require_once '../app/controllers/GrupoController.php';
        break;
    
    case '/grupos/asignar_profesor':
        require_once '../app/controllers/ProfesorGrupoController.php';
        break;

    case 'profesores/asignar_resumen':
        require_once '../app/controllers/ProfesorGrupoController.php';
        break;

    // Dashboard de Alumnos
    case '/alumnos/horario':
        require_once '../app/views/alumnos/horario.php';
        break;

    case '/alumnos/tareas':
        require_once '../app/views/alumnos/tareas.php';
        break;

    case '/alumnos/calificaciones':
        require_once '../app/views/alumnos/calificaciones.php';
        break;



    // Dashboard de Profesores
    case '/profesores/dashboard':
        require_once '../app/views/profesores/dashboard.php';
        break;

    case '/profesores/grupos':
        require_once '../app/controllers/ProfesorGrupoController.php';
        break;

        // Tareas
    case '/profesores/tareas':
        require_once '../app/views/profesores/tareas_opciones.php';
        break;
    
    case '/profesores/tareas_formulario':
        require_once '../app/views/profesores/tareas_formulario.php';
        break;

    case '/profesores/registrar_tarea':
        require_once '../app/controllers/TareaController.php';
        break;

    case '/profesores/tareas/guardar':
        require_once '../app/controllers/TareaController.php';
        break;

    case '/profesores/calificar_tarea':
        require_once '../app/controllers/TareaController.php';
        break;

    case '/profesores/calificar_tarea_detalle':
        require_once '../app/controllers/TareaController.php';
        break;

    case '/profesores/tareas/calificar':
        require_once '../app/controllers/TareaController.php';
        break;

    case '/profesores/tareas/editar':
        require_once '../app/controllers/TareaController.php';
        break;

    case '/profesores/tareas/eliminar':
        require_once '../app/controllers/TareaController.php';
        break;
    
        // Calificaciones       
    case '/profesores/calificaciones':
        require_once '../app/controllers/CalificacionesController.php';
        break;
    
    case '/profesores/calificaciones/final':
        require_once '../app/controllers/CalificacionesController.php';
        break;
        
    case '/profesores/calificaciones/alumno':
        require_once '../app/controllers/CalificacionesController.php';
        break;

    case '/profesores/boleta':
        require_once '../app/controllers/CalificacionesController.php';
        break;

     





    // test
    case '/test':
        echo "Ruta /test funcionando correctamente.";
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada.";
        break;
}
