<?php
session_start();

// Verificar si existe una sesión activa
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['rol'])) {
    header("Location: index.html");
    exit();
}

// Verificar si el usuario es estudiante
if ($_SESSION['usuario']['rol'] != 2) {
    header("Location: index.html");
    exit();
}

// Protección XSS para el nombre del usuario
$nombre_estudiante = htmlspecialchars($_SESSION['usuario']['nombre'], ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Estudiante</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <div class="panel-box">
            <h2>Bienvenido Estudiante: <?php echo $nombre_estudiante; ?></h2>
            
            <div class="menu-options">
                <a href="ver_notas.php" class="btn btn-primary">Ver mis notas</a>
                <a href="ver_asignaturas.php" class="btn btn-secondary">Mis Asignaturas</a>
                <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
            </div>
        </div>
    </div>
</body>
</html>
