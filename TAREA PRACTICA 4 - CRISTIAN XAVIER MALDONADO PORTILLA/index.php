<?php
include 'db.php';

// Verificar si el usuario está autenticado
if (isset($_SESSION['usuario'])) {
    header("Location: tareas.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de gestión de tareas - Organiza tus actividades diarias">
    <title>Bienvenido - Lista de Tareas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenido al Sistema de Tareas</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error">
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="welcome-text">
            <p>Gestiona tus tareas de manera eficiente según tu rol.</p>
            <ul class="features-list">
                <li>✓ Organiza tus actividades diarias</li>
                <li>✓ Seguimiento de tareas completadas</li>
                <li>✓ Gestión según nivel de usuario</li>
            </ul>
        </div>

        <div class="auth-buttons">
            <a href="login.php" class="button primary">Iniciar Sesión</a>
            <a href="register.php" class="button secondary">Registrarse</a>
        </div>

        <footer class="page-footer">
            <p>Sistema desarrollado por Cristian Xavier Maldonado Portilla</p>
        </footer>
    </div>
</body>
</html>
