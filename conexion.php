<?php
// Configuración para Railway usando variables de entorno
$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
$usuario = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root';
$clave = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '';
$base_datos = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'vidia';
$puerto = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? 3306;

// Crear conexión con puerto específico

try {
    $dsn = "mysql:host=$host;port=$puerto;dbname=$base_datos;charset=utf8";
    $conexion = new PDO($dsn, $usuario, $clave);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Opcional: mostrar info de conexión en desarrollo
    if (isset($_GET['debug']) && $_GET['debug'] == 'db') {
        echo "✅ Conectado a: " . $host . ":" . $puerto . " | DB: " . $base_datos . "<br>";
    }
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!-- <?php
//$conexion = new mysqli("localhost", "root", "root", "vidia");
//if ($conexion->connect_error) {
 // die("Error de conexión: " . $conexion->connect_error);
//}
?> -->