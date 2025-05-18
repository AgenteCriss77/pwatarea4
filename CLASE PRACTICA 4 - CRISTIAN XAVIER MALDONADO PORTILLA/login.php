<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Calificaciones</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <form action="login.php" method="POST" class="login-form">
                <h2>Iniciar Sesión</h2>
                
                <?php
                session_start();
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                    unset($_SESSION['error']);
                }
                ?>
                
                <div class="form-group">
                    <label for="email">Correo electrónico:</label>
                    <input type="email" id="email" name="email" placeholder="Ingrese su correo electrónico" required>
                </div>

                <div class="form-group">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" required>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Ingresar</button>
                </div>

                <div class="links">
                    <a href="registro.php">¿No tienes cuenta? Regístrate</a>
                    <br>
                    <a href="index.html">Volver al inicio</a>
                </div>
            </form>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.html");
    exit();
}

// Sanitizar las entradas
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$contrasena = $_POST['contrasena'];

// Validar el email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Email inválido";
    header("Location: login.html");
    exit();
}

// Preparar la consulta para prevenir SQL Injection
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    
    // Verificar la contraseña
    if (password_verify($contrasena, $usuario['contrasena'])) {
        // Almacenar datos importantes en la sesión
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'rol' => $usuario['rol'],
            'email' => $usuario['email']
        ];
        
        // Redirigir según el rol
        if ($usuario['rol'] == 1) {
            header("Location: panel_docente.php");
        } else {
            header("Location: panel_estudiante.php");
        }
        exit();
    }
}

$_SESSION['error'] = "Credenciales incorrectas";
header("Location: login.html");
exit();

$stmt->close();
$conn->close();
?>
