<?php
include 'db.php';

// Redirigir si ya está autenticado
if (isset($_SESSION['usuario'])) {
    header("Location: tareas.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar campos vacíos
    if (empty($_POST['nombre']) || empty($_POST['usuario']) || empty($_POST['clave'])) {
        $error = "Todos los campos son obligatorios";
    } else {
        // Sanitizar entradas
        $nombre = trim(htmlspecialchars($_POST['nombre']));
        $usuario = trim(htmlspecialchars($_POST['usuario']));
        $clave = $_POST['clave'];
        // Por defecto, asignar rol de usuario
        $rol = 'usuario';

        try {
            // Verificar si el usuario ya existe
            $check = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
            $check->bind_param("s", $usuario);
            $check->execute();
            $resultado = $check->get_result();

            if ($resultado->num_rows > 0) {
                $error = "El nombre de usuario ya está en uso";
            } else {
                // Hash de la contraseña
                $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

                // Insertar nuevo usuario
                $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, usuario, clave, rol) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $nombre, $usuario, $clave_hash, $rol);
                
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Registro exitoso. Por favor, inicia sesión.";
                    header("Location: login.php");
                    exit;
                } else {
                    throw new Exception("Error al registrar el usuario");
                }
            }
        } catch (Exception $e) {
            $error = "Error en el sistema. Por favor, intente más tarde.";
            error_log("Error de registro: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Tareas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <h1>Registro de Usuario</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="register-form">
            <div class="form-group">
                <label for="nombre">Nombre completo:</label>
                <input type="text" id="nombre" name="nombre" required 
                       value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="usuario">Nombre de usuario:</label>
                <input type="text" id="usuario" name="usuario" required 
                       value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="clave">Contraseña:</label>
                <input type="password" id="clave" name="clave" required>
            </div>

            <button type="submit" class="button primary">Registrarse</button>
        </form>

        <div class="links">
            <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
            <a href="index.php">Volver al inicio</a>
        </div>
    </div>
</body>
</html>
