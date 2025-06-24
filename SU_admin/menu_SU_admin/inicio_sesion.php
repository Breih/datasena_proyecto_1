<?php
session_start();

// Redirigir si ya hay sesión activa
if (isset($_SESSION['usuario'])) {
    header("Location: super.menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Inicio de Sesión Super Admin</title>
    <link rel="icon" href="../../img/Logotipo_Datasena.png" type="image/x-icon" />
    <link rel="stylesheet" href="../../css/SU_admin/menu_SU_admin/super.css" />

    <script>
        function validarFormulario() {
            const usuario = document.getElementById('usuario').value.trim();
            const contraseña = document.getElementById('contraseña').value.trim();

            if (usuario === '' || contraseña === '') {
                alert('Por favor, complete todos los campos.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <header>DATASENA</header>
    <img src="../../img/logo-sena.png" alt="Logo SENA" class="sena-logo" />

    <div class="form-container">
        <h2>Inicio de Sesión</h2>

        <?php if (isset($_GET['error'])): ?>
            <p style="color:red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <form action="super_menu.html" method="POST" onsubmit="return validarFormulario()">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario" required />

            <label for="contraseña">Contraseña</label>
            <input type="password" id="contraseña" name="contraseña" placeholder="Ingrese su contraseña" required />

            <button type="submit">Ingresar</button>
        </form>

        <br>
        <button onclick="window.location.href='../../inicio_todos.html'">Regresar</button>
        <a href="recuperarCon.html">Restaurar contraseña</a>
    </div>

    <footer>
        <a>© Todos los derechos reservados al SENA</a>
    </footer>
</body>
</html>