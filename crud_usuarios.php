<?php
include 'conexion.php';
session_start();

// Verificar que esté logueado y sea administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: index.php");
    exit();
}

// Procesar formulario de guardar
if (isset($_POST['guardar'])) {
    $id = $_POST['id'];
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $clave = mysqli_real_escape_string($conexion, $_POST['clave']);
    $apellidos = mysqli_real_escape_string($conexion, $_POST['apellidos']);
    $nombres = mysqli_real_escape_string($conexion, $_POST['nombres']);
    $dni = mysqli_real_escape_string($conexion, $_POST['dni']);
    $rol = $_POST['rol'];
    $estado = $_POST['estado'];
    
    if ($id) {
        // Actualizar usuario existente
        $sql = "UPDATE tbl_usuario SET 
                usuario='$usuario', 
                clave='$clave', 
                apellidos='$apellidos',
                nombres='$nombres',
                dni='$dni',
                rol='$rol', 
                estado='$estado' 
                WHERE id=$id";
        
        if (mysqli_query($conexion, $sql)) {
            header("Location: usuarios.php?msg=updated");
        } else {
            header("Location: usuarios.php?error=update_failed");
        }
    } else {
        // Verificar si el usuario ya existe
        $check_sql = "SELECT id FROM tbl_usuario WHERE usuario='$usuario'";
        $check_result = mysqli_query($conexion, $check_sql);
        
        if (mysqli_num_rows($check_result) > 0) {
            header("Location: usuarios.php?error=user_exists");
        } else {
            // Crear nuevo usuario
            $sql = "INSERT INTO tbl_usuario(usuario, clave, apellidos, nombres , dni, rol, estado) 
                    VALUES('$usuario', '$clave', '$apellidos', '$nombres', '$dni', '$rol', '$estado')";

            if (mysqli_query($conexion, $sql)) {
                header("Location: usuarios.php?msg=created");
            } else {
                header("Location: usuarios.php?error=create_failed");
            }
        }
    }
}

// Procesar eliminación
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    
    // No permitir eliminar al usuario actual
    $current_user_sql = "SELECT id FROM tbl_usuario WHERE usuario='" . $_SESSION['usuario'] . "'";
    $current_user_result = mysqli_query($conexion, $current_user_sql);
    $current_user = mysqli_fetch_assoc($current_user_result);
    
    if ($current_user['id'] != $id) {
        $sql = "DELETE FROM tbl_usuario WHERE id=$id";
        
        if (mysqli_query($conexion, $sql)) {
            header("Location: usuarios.php?msg=deleted");
        } else {
            header("Location: usuarios.php?error=delete_failed");
        }
    } else {
        header("Location: usuarios.php?error=cannot_delete_self");
    }
}

mysqli_close($conexion);
?>