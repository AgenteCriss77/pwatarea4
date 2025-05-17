<?php
include 'db.php';

// Verificar si hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

// Verificar si se recibieron datos por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['descripcion'])) {
    header("Location: tareas.php");
    exit;
}

// Sanitizar la entrada
$desc = trim(htmlspecialchars($_POST['descripcion']));

// Verificar que la descripción no esté vacía
if (empty($desc)) {
    $_SESSION['error'] = "La descripción no puede estar vacía";
    header("Location: tareas.php");
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];

try {
    $stmt = $conexion->prepare("INSERT INTO tareas (descripcion, usuario_id) VALUES (?, ?)");
    $stmt->bind_param("si", $desc, $usuario_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al guardar la tarea");
    }
    
    $_SESSION['success'] = "Tarea agregada correctamente";
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
} finally {
    $stmt->close();
}

header("Location: tareas.php");
exit;
?>
