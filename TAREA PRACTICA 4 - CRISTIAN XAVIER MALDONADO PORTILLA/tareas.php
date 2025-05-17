<?php 
include 'db.php';

// Verificar autenticación
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
$rol = $usuario['rol'];

try {
    // Preparar consulta según el rol
    if ($rol == 'admin') {
        $sql = "SELECT t.*, u.nombre FROM tareas t JOIN usuarios u ON t.usuario_id = u.id ORDER BY t.id DESC";
        $stmt = $conexion->prepare($sql);
    } else {
        $sql = "SELECT * FROM tareas WHERE usuario_id = ? ORDER BY id DESC";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $usuario['id']);
    }
    
    $stmt->execute();
    $resultado = $stmt->get_result();
} catch (Exception $e) {
    $_SESSION['error'] = "Error al cargar las tareas: " . $e->getMessage();
    error_log("Error en tareas.php: " . $e->getMessage());
    $resultado = false;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de gestión de tareas">
    <title>Mis Tareas - Sistema de Gestión</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="tareas.js" defer></script>
</head>
<body>
    <div class="container">
        <header class="main-header">
            <h1>Sistema de Tareas</h1>
            <div class="user-info">
                <p>Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?> 
                   <span class="role-badge">(<?php echo htmlspecialchars($rol); ?>)</span>
                </p>
                <a href="logout.php" class="button logout">Cerrar Sesión</a>
            </div>
        </header>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <?php 
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <?php 
                    echo htmlspecialchars($_SESSION['success']);
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <section class="add-task-section">
            <form action="add_tarea.php" method="POST" class="task-form">
                <div class="form-group">
                    <input type="text" name="descripcion" 
                           placeholder="Escribe una nueva tarea" 
                           required 
                           class="task-input"
                           maxlength="255">
                    <button type="submit" class="button primary">Agregar Tarea</button>
                </div>
            </form>
        </section>

        <section class="tasks-section">
            <h2>Lista de Tareas</h2>
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <ul class="tareas-lista">
                    <?php while ($tarea = $resultado->fetch_assoc()): ?>
                        <li class="tarea-item">
                            <div class="tarea-contenido">
                                <?php if ($rol == 'admin'): ?>
                                    <span class="usuario-tarea">
                                        <?php echo htmlspecialchars($tarea['nombre']); ?>:
                                    </span>
                                <?php endif; ?>
                                <span class="descripcion-tarea">
                                    <?php echo htmlspecialchars($tarea['descripcion']); ?>
                                </span>
                            </div>
                            <div class="tarea-acciones">
                                <form action="delete_tarea.php" method="POST" class="delete-form">
                                    <input type="hidden" name="tarea_id" 
                                           value="<?php echo $tarea['id']; ?>">
                                    <button type="submit" 
                                            class="button delete"
                                            onclick="return confirm('¿Estás seguro de eliminar esta tarea?')">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p class="no-tasks">No hay tareas para mostrar.</p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
