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

// Control de permisos
if ($rol == 3 && ($accion == 'agregar' || $accion == 'modificar' || $accion == 'eliminar')) {
    die("No tienes permisos para realizar esta acción. Solo puedes consultar información.");
}

if ($rol == 2 && $accion == 'eliminar') {
    die("Como docente no tienes permisos para eliminar registros de estudiantes.");
}

// Procesar formulario de guardar
if (isset($_POST['guardar'])) {
    $id = $_POST['id'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombres']);
    $apellidos = mysqli_real_escape_string($conexion, $_POST['apellidos']);
    $dni = mysqli_real_escape_string($conexion, $_POST['dni']);
    $sexo = $_POST['sexo'];
    $estado = $_POST['estado'];
    $programa = $_POST['programa'];
    $ciclo = $_POST['ciclo'];
    $turno = $_POST['turno'];

    if ($id) {
        // Actualizar estudiante existente
        $sql = "UPDATE tbl_estudiante SET 
                nombre='$nombre', 
                apellidos='$apellidos', 
                dni='$dni', 
                sexo='$sexo', 
                estado='$estado', 
                programa='$programa', 
                ciclo='$ciclo', 
                turno='$turno' 
                WHERE id=$id";
        
        if (mysqli_query($conexion, $sql)) {
            header("Location: estudiantes.php?msg=updated");
        } else {
            header("Location: estudiantes.php?error=update_failed");
        }
    } else {
        // Crear nuevo estudiante
        $sql = "INSERT INTO tbl_estudiante(dni, nombre, apellidos, sexo, estado, programa, ciclo , turno) 
                VALUES('$dni', '$nombre', '$apellidos', '$sexo', '$estado', '$programa', '$ciclo', '$turno')";
        
        if (mysqli_query($conexion, $sql)) {
            header("Location: estudiantes.php?msg=created");
        } else {
            header("Location: estudiantes.php?error=create_failed");
        }
    }
}

// Procesar eliminación
if (isset($_GET['eliminar']) && $rol == 1) {
    $id = $_GET['eliminar'];
    $sql = "DELETE FROM tbl_estudiante WHERE id=$id";
    
    if (mysqli_query($conexion, $sql)) {
        header("Location: estudiantes.php?msg=deleted");
    } else {
        header("Location: estudiantes.php?error=delete_failed");
    }
}

mysqli_close($conexion);
?>