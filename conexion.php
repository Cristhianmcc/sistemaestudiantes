<?php
$conexion = new mysqli("localhost", "root", "root", "vidia");
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}
?>