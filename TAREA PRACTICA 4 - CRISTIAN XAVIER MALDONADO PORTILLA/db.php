<?php
session_start();

$host = 'localhost';
$usuario = 'root';
$clave = '';
$base_datos = 'lista_tareas';

try {
    $conexion = new mysqli($host, $usuario, $clave, $base_datos);
    $conexion->set_charset("utf8mb4");
    
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>