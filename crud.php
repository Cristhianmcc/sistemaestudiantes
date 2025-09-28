<?php
include 'conexion.php';
session_start();

// Verificar que esté logueado
if (!isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit();
}

$rol = $_SESSION['rol'];
$accion = $_POST['accion'] ?? '';

// Control de permisos actualizados
// Estudiante (rol 3): Solo puede consultar
if ($rol == 3 && ($accion == 'agregar' || $accion == 'modificar' || $accion == 'eliminar')) {
    die("No tienes permisos para realizar esta acción. Solo puedes consultar información.");
}

// Docente (rol 2): Puede agregar y modificar, pero no eliminar
if ($rol == 2 && $accion == 'eliminar') {
    die("Como docente no tienes permisos para eliminar registros de estudiantes.");
}

// Administrador (rol 1): Tiene todos los permisos

// Procesar formulario de guardar (compatibilidad con versión anterior)
if (isset($_POST['guardar'])) {
    $id = $_POST['id'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombres']);
    $apellidos = mysqli_real_escape_string($conexion, $_POST['apellidos']);
    $dni = mysqli_real_escape_string($conexion, $_POST['dni']);
    $sexo = $_POST['sexo'];
    $estado = $_POST['estado'];
    $programa = $_POST['programa'];
    $ciclo = $_POST['ciclo'];
    
    if ($id) {
        // Actualizar estudiante existente
        $sql = "UPDATE tbl_estudiante SET 
                nombre='$nombre', 
                apellidos='$apellidos', 
                dni='$dni', 
                sexo='$sexo', 
                estado='$estado', 
                programa='$programa', 
                ciclo='$ciclo' 
                WHERE id=$id";
        mysqli_query($conexion, $sql);
    } else {
        // Crear nuevo estudiante
        $sql = "INSERT INTO tbl_estudiante(dni, nombre, apellidos, sexo, estado, programa, ciclo) 
                VALUES('$dni', '$nombre', '$apellidos', '$sexo', '$estado', '$programa', '$ciclo')";
        mysqli_query($conexion, $sql);
    }
    header("Location: index.php");
}

// Procesar eliminación (compatibilidad con versión anterior)
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    mysqli_query($conexion, "DELETE FROM tbl_estudiante WHERE id=$id");
    header("Location: index.php");
}

mysqli_close($conexion);
?>