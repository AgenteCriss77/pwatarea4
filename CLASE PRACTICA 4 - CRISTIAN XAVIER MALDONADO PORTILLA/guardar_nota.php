<?php
session_start();
include 'conexion.php';

// Verificar si el usuario es docente
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar y validar entradas
    $usuario_id = filter_var($_POST['usuario_id'], FILTER_VALIDATE_INT);
    $asignatura_id = filter_var($_POST['asignatura_id'], FILTER_VALIDATE_INT);
    $parcial = filter_var($_POST['parcial'], FILTER_VALIDATE_INT);
    $teoria = filter_var($_POST['teoria'], FILTER_VALIDATE_FLOAT);
    $practica = filter_var($_POST['practica'], FILTER_VALIDATE_FLOAT);
    
    // Validaciones
    if (!$usuario_id || !$asignatura_id || !in_array($parcial, [1,2,3]) || 
        $teoria < 0 || $teoria > 10 || $practica < 0 || $practica > 10) {
        $_SESSION['error'] = "Datos inválidos";
        header("Location: ingresar_notas.php");
        exit();
    }

    try {
        // Verificar si ya existe una nota para este estudiante en esta asignatura y parcial
        $stmt = $conn->prepare("SELECT id FROM notas WHERE usuario_id = ? AND asignatura_id = ? AND parcial = ?");
        $stmt->bind_param("iii", $usuario_id, $asignatura_id, $parcial);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Actualizar nota existente
            $sql = "UPDATE notas SET teoria = ?, practica = ?, usuario_id_actualizacion = ?, 
                    fecha_actualizacion = CURRENT_TIMESTAMP, hora_actualizacion = CURRENT_TIME 
                    WHERE usuario_id = ? AND asignatura_id = ? AND parcial = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ddiiii", $teoria, $practica, $_SESSION['usuario']['id'], 
                            $usuario_id, $asignatura_id, $parcial);
        } else {
            // Insertar nueva nota
            $sql = "INSERT INTO notas (usuario_id, asignatura_id, parcial, teoria, practica, 
                    usuario_id_creacion, fecha_creacion, hora_creacion) 
                    VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIME)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiiddi", $usuario_id, $asignatura_id, $parcial, $teoria, 
                            $practica, $_SESSION['usuario']['id']);
        }

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Calificación guardada exitosamente";
        } else {
            $_SESSION['error'] = "Error al guardar la calificación";
        }
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Error en la base de datos";
    }

    $stmt->close();
    header("Location: ingresar_notas.php");
    exit();
}

$conn->close();
?>
<?php
session_start();
include 'conexion.php';

// Verificar si el usuario es docente
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar y validar entradas
    $usuario_id = filter_var($_POST['usuario_id'], FILTER_VALIDATE_INT);
    $asignatura_id = filter_var($_POST['asignatura_id'], FILTER_VALIDATE_INT);
    $parcial = filter_var($_POST['parcial'], FILTER_VALIDATE_INT);
    $teoria = filter_var($_POST['teoria'], FILTER_VALIDATE_FLOAT);
    $practica = filter_var($_POST['practica'], FILTER_VALIDATE_FLOAT);
    
    // Validaciones
    if (!$usuario_id || !$asignatura_id || !in_array($parcial, [1,2,3]) || 
        $teoria < 0 || $teoria > 10 || $practica < 0 || $practica > 10) {
        $_SESSION['error'] = "Datos inválidos";
        header("Location: ingresar_notas.php");
        exit();
    }

    try {
        // Verificar si ya existe una nota para este estudiante en esta asignatura y parcial
        $stmt = $conn->prepare("SELECT id FROM notas WHERE usuario_id = ? AND asignatura_id = ? AND parcial = ?");
        $stmt->bind_param("iii", $usuario_id, $asignatura_id, $parcial);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Actualizar nota existente
            $sql = "UPDATE notas SET teoria = ?, practica = ?, usuario_id_actualizacion = ?, 
                    fecha_actualizacion = CURRENT_TIMESTAMP, hora_actualizacion = CURRENT_TIME 
                    WHERE usuario_id = ? AND asignatura_id = ? AND parcial = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ddiiii", $teoria, $practica, $_SESSION['usuario']['id'], 
                            $usuario_id, $asignatura_id, $parcial);
        } else {
            // Insertar nueva nota
            $sql = "INSERT INTO notas (usuario_id, asignatura_id, parcial, teoria, practica, 
                    usuario_id_creacion, fecha_creacion, hora_creacion) 
                    VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIME)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiiddi", $usuario_id, $asignatura_id, $parcial, $teoria, 
                            $practica, $_SESSION['usuario']['id']);
        }

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Calificación guardada exitosamente";
        } else {
            $_SESSION['error'] = "Error al guardar la calificación";
        }
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Error en la base de datos";
    }

    $stmt->close();
    header("Location: ingresar_notas.php");
    exit();
}

$conn->close();
?>