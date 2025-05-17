<?php
include 'db.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

// Verificar si se recibió el ID y si es un número válido
if (!isset($_POST['tarea_id']) || !is_numeric($_POST['tarea_id'])) {
    $_SESSION['error'] = "ID de tarea inválido";
    header("Location: tareas.php");
    exit;
}

$id = intval($_POST['tarea_id']);
$usuario = $_SESSION['usuario'];

try {
    // Preparar la consulta según el rol del usuario
    if ($usuario['rol'] == 'admin') {
        $stmt = $conexion->prepare("DELETE FROM tareas WHERE id = ?");
        $stmt->bind_param("i", $id);
    } else {
        $stmt = $conexion->prepare("DELETE FROM tareas WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $id, $usuario['id']);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['success'] = "Tarea eliminada correctamente";
        } else {
            $_SESSION['error'] = "No se encontró la tarea o no tienes permisos para eliminarla";
        }
    } else {
        throw new Exception("Error al eliminar la tarea");
    }

    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Error al eliminar la tarea: " . $e->getMessage();
}

header("Location: tareas.php");
exit;
?>
