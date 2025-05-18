<?php
session_start();
include 'conexion.php';

// Verificar si el usuario es docente
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] != 1) {
    header("Location: login.php");
    exit();
}

// Obtener lista de estudiantes
$sql_estudiantes = "SELECT id, nombre FROM usuarios WHERE rol = 2";
$resultado_estudiantes = $conn->query($sql_estudiantes);

// Obtener lista de asignaturas
$sql_asignaturas = "SELECT id, nombre FROM asignaturas";
$resultado_asignaturas = $conn->query($sql_asignaturas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar Notas - Sistema de Calificaciones</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <div class="notas-box">
            <h2>Ingresar Calificaciones</h2>
            
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['mensaje'];
                    unset($_SESSION['mensaje']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="guardar_nota.php" method="POST" class="registro-form">
                <div class="form-group">
                    <label for="usuario_id">Estudiante:</label>
                    <select name="usuario_id" id="usuario_id" required>
                        <option value="">Seleccione un estudiante</option>
                        <?php while($estudiante = $resultado_estudiantes->fetch_assoc()): ?>
                            <option value="<?php echo $estudiante['id']; ?>">
                                <?php echo htmlspecialchars($estudiante['nombre']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="asignatura_id">Asignatura:</label>
                    <select name="asignatura_id" id="asignatura_id" required>
                        <option value="">Seleccione una asignatura</option>
                        <?php while($asignatura = $resultado_asignaturas->fetch_assoc()): ?>
                            <option value="<?php echo $asignatura['id']; ?>">
                                <?php echo htmlspecialchars($asignatura['nombre']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="parcial">Parcial:</label>
                    <select name="parcial" id="parcial" required>
                        <option value="">Seleccione el parcial</option>
                        <option value="1">Primer Parcial</option>
                        <option value="2">Segundo Parcial</option>
                        <option value="3">Mejoramiento</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="teoria">Nota Teoría (0-10):</label>
                    <input type="number" id="teoria" name="teoria" required 
                           min="0" max="10" step="0.01">
                </div>

                <div class="form-group">
                    <label for="practica">Nota Práctica (0-10):</label>
                    <input type="number" id="practica" name="practica" required 
                           min="0" max="10" step="0.01">
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Guardar Calificación</button>
                    <a href="panel_docente.php" class="btn btn-secondary">Volver al Panel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
