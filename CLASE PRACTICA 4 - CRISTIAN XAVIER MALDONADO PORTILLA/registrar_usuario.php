<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitización y validación de entradas
    $nombre = filter_var(trim($_POST['nombre']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $contrasena = $_POST['contrasena'];
    $rol = filter_var($_POST['rol'], FILTER_VALIDATE_INT);

    // Validaciones
    $errores = array();

    if (empty($nombre) || strlen($nombre) < 3) {
        $errores[] = "El nombre debe tener al menos 3 caracteres";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Email inválido";
    }

    if (strlen($contrasena) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres";
    }

    if ($rol !== 1 && $rol !== 2) {
        $errores[] = "Rol inválido";
    }

    // Verificar si el email ya existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errores[] = "El email ya está registrado";
    }
    $stmt->close();

    if (empty($errores)) {
        // Encriptar contraseña
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Preparar la consulta
        $sql = "INSERT INTO usuarios (nombre, email, contrasena, rol, fecha_creacion, hora_creacion) 
                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIME)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nombre, $email, $contrasena_hash, $rol);

        if ($stmt->execute()) {
            // Registro exitoso
            session_start();
            $_SESSION['mensaje'] = "Registro exitoso. Por favor, inicia sesión.";
            header("Location: login.php");
            exit();
        } else {
            $errores[] = "Error al registrar: " . $stmt->error;
        }
        $stmt->close();
    }

    if (!empty($errores)) {
        // Almacenar errores en sesión para mostrarlos
        session_start();
        $_SESSION['errores'] = $errores;
        header("Location: registro.html");
        exit();
    }
} else {
    header("Location: registro.html");
    exit();
}

$conn->close();
?>
