<?php
// Configuración para Railway usando variables de entorno
$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
$usuario = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root';
$clave = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '';
$base_datos = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'vidia';
$puerto = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? 3306;

// Crear conexión con puerto específico
$conexion = new mysqli($host, $usuario, $clave, $base_datos, $puerto);

// Verificar conexión
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

// Establecer charset UTF-8
$conexion->set_charset("utf8");

// Opcional: mostrar info de conexión en desarrollo
if (isset($_GET['debug']) && $_GET['debug'] == 'db') {
  echo "✅ Conectado a: " . $host . ":" . $puerto . " | DB: " . $base_datos . "<br>";
}
?>

<!-- <?php
//$conexion = new mysqli("localhost", "root", "root", "vidia");
//if ($conexion->connect_error) {
 // die("Error de conexión: " . $conexion->connect_error);
//}
?> -->