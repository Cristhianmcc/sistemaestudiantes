<?php
// Iniciar sesión para manejar mensajes y redirecciones
session_start();

// Si ya está logueado, redirigir al índice
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Procesar login si es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include 'conexion.php';
    
    $usuario = trim($_POST["usuario"] ?? '');
    $clave = trim($_POST["clave"] ?? '');

    if (empty($usuario) || empty($clave)) {
        header("Location: login.php?error=empty_fields");
        exit();
    }

    // Consulta preparada para mayor seguridad
    $stmt = $conexion->prepare("SELECT * FROM tbl_usuario WHERE usuario = ? AND clave = ? AND estado = 1");
    $stmt->bind_param("ss", $usuario, $clave);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $fila = $resultado->fetch_assoc();
      
        // Establecer variables de sesión
        $_SESSION['usuario'] = $fila['usuario'];
        $_SESSION['rol'] = $fila['rol'];
        $_SESSION['usuario_id'] = $fila['id'];
        $_SESSION['dni'] = $fila['dni']; // DNI para vinculación con estudiantes
        
        header("Location: index.php?login=success");
        exit();
    } else {
        header("Location: login.php?error=invalid_credentials");
        exit();
    }

    $stmt->close();
    $conexion->close();
} else {
    // Si no es POST, redirigir al formulario de login
    header("Location: login.php");
    exit();
}
?>