<?php
include 'db.php';

// Redirigir si ya está autenticado
if (isset($_SESSION['usuario'])) {
    header("Location: tareas.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que los campos no estén vacíos
    if (empty($_POST['usuario']) || empty($_POST['clave'])) {
        $error = "Por favor, complete todos los campos";
    } else {
        // Sanitizar entradas
        $usuario = trim(htmlspecialchars($_POST['usuario']));
        $clave = $_POST['clave'];

        try {
            $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ?");
            $stmt->bind_param("s", $usuario);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $usuario_data = $resultado->fetch_assoc();

            if ($usuario_data && password_verify($clave, $usuario_data['clave'])) {
                $_SESSION['usuario'] = $usuario_data;
                header("Location: tareas.php");
                exit;
            } else {
                $error = "Usuario o contraseña incorrectos";
            }
            $stmt->close();
        } catch (Exception $e) {
            $error = "Error en el sistema. Por favor, intente más tarde.";
            error_log("Error de login: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Tareas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <h1>Iniciar Sesión</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="login-form">
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required 
                       value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="clave">Contraseña:</label>
                <input type="password" id="clave" name="clave" required>
            </div>

            <button type="submit" class="button primary">Iniciar Sesión</button>
        </form>

        <div class="links">
            <a href="register.php">¿No tienes cuenta? Regístrate</a>
            <a href="index.php">Volver al inicio</a>
        </div>
    </div>
</body>
</html>
