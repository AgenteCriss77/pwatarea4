<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Registro de usuarios - Sistema de Calificaciones">
    <title>Registro de Usuario - Sistema de Calificaciones</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <div class="registro-box">
            <h2>Registro de Usuario</h2>
            
            <?php
            session_start();
            if (isset($_SESSION['errores'])) {
                echo '<div class="alert alert-danger">';
                foreach ($_SESSION['errores'] as $error) {
                    echo "<p>" . htmlspecialchars($error) . "</p>";
                }
                echo '</div>';
                unset($_SESSION['errores']);
            }
            if (isset($_SESSION['exito'])) {
                echo '<div class="alert alert-success">';
                echo "<p>" . htmlspecialchars($_SESSION['exito']) . "</p>";
                echo '</div>';
                unset($_SESSION['exito']);
            }
            ?>

            <form action="registrar_usuario.php" method="POST" class="registro-form" id="registroForm">
                <div class="form-group">
                    <label for="nombre">Nombre completo:</label>
                    <input type="text" id="nombre" name="nombre" required 
                           minlength="3" maxlength="100" 
                           placeholder="Ingrese su nombre completo"
                           value="<?php echo isset($_SESSION['old_input']['nombre']) ? htmlspecialchars($_SESSION['old_input']['nombre']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico:</label>
                    <input type="email" id="email" name="email" required 
                           maxlength="100" 
                           placeholder="ejemplo@dominio.com"
                           value="<?php echo isset($_SESSION['old_input']['email']) ? htmlspecialchars($_SESSION['old_input']['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" 
                           required minlength="6" 
                           placeholder="Mínimo 6 caracteres">
                </div>

                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select id="rol" name="rol" required>
                        <option value="">Seleccione un rol</option>
                        <option value="1" <?php echo (isset($_SESSION['old_input']['rol']) && $_SESSION['old_input']['rol'] == 1) ? 'selected' : ''; ?>>Docente</option>
                        <option value="2" <?php echo (isset($_SESSION['old_input']['rol']) && $_SESSION['old_input']['rol'] == 2) ? 'selected' : ''; ?>>Estudiante</option>
                    </select>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                    <button type="reset" class="btn btn-secondary">Limpiar</button>
                </div>
            </form>

            <div class="links">
                <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
                <br>
                <a href="index.html">Volver al inicio</a>
            </div>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>
</html>

<?php
// Limpiar datos antiguos después de mostrarlos
if (isset($_SESSION['old_input'])) {
    unset($_SESSION['old_input']);
}
?>