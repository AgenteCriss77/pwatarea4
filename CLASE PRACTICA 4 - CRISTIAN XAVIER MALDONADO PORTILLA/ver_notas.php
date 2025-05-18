<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    header("Location: login.html");
    exit();
}

include 'conexion.php';

// Obtener información del usuario
$usuario_id = filter_var($_SESSION['usuario']['id'], FILTER_VALIDATE_INT);
$es_docente = ($_SESSION['usuario']['rol'] == 1);

try {
    // Consulta principal con JOIN para obtener nombres de asignaturas
    $sql = "SELECT n.*, a.nombre as nombre_asignatura 
            FROM notas n 
            INNER JOIN asignaturas a ON n.asignatura_id = a.id 
            WHERE n.usuario_id = ? 
            ORDER BY a.nombre, n.parcial";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas - Sistema de Calificaciones</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <div class="notas-box">
            <h2>Registro de Calificaciones</h2>
            
            <?php if ($resultado->num_rows > 0) { ?>
                <table class="tabla-notas">
                    <thead>
                        <tr>
                            <th>Asignatura</th>
                            <th>Parcial</th>
                            <th>Teoría</th>
                            <th>Práctica</th>
                            <th>Promedio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($nota = $resultado->fetch_assoc()) { 
                            $promedio = ($nota['teoria'] + $nota['practica']) / 2;
                            $estado = $promedio >= 7 ? 'aprobado' : 'reprobado';
                        ?>
                            <tr class="<?php echo $estado; ?>">
                                <td><?php echo htmlspecialchars($nota['nombre_asignatura']); ?></td>
                                <td><?php echo $nota['parcial'] == 3 ? 'Mejoramiento' : $nota['parcial'] . '° Parcial'; ?></td>
                                <td><?php echo number_format($nota['teoria'], 2); ?></td>
                                <td><?php echo number_format($nota['practica'], 2); ?></td>
                                <td><?php echo number_format($promedio, 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="mensaje-info">
                    No hay calificaciones registradas.
                </div>
            <?php } ?>

            <div class="button-group">
                <a href="<?php echo $es_docente ? 'panel_docente.php' : 'panel_estudiante.php'; ?>" class="btn btn-secondary">Volver al Panel</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    $stmt->close();
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error al cargar las calificaciones.</div>";
} finally {
    $conn->close();
}
?>
