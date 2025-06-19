<?php
// empresaRe_su.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexión a la base de datos
    $host = "localhost";
    $db = "datasenn_db";
    $user = "root";
    $pass = ""; // Sin contraseña

    try {
        $conexion = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Recoger datos del formulario
        $tipo_documento = $_POST['tipo_documento'] ?? '';
        $nit = $_POST['nit'] ?? '';
        $nickname = $_POST['nickname'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $rol = $_POST['rol'] ?? '';
        $actividad_economica = $_POST['actividad_economica'] ?? '';

        // Validar campos obligatorios
        if (
            empty($tipo_documento) || empty($nit) || empty($nickname) ||
            empty($telefono) || empty($correo) || empty($direccion) ||
            empty($rol) || empty($actividad_economica)
        ) {
            $error = "Por favor completa todos los campos obligatorios.";
        } else {
            // Insertar datos
            $sql = "INSERT INTO empresas (tipo_documento, nit, nickname, telefono, correo, direccion, rol, actividad_economica) 
                    VALUES (:tipo_documento, :nit, :nickname, :telefono, :correo, :direccion, :rol, :actividad_economica)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':tipo_documento', $tipo_documento);
            $stmt->bindParam(':nit', $nit);
            $stmt->bindParam(':nickname', $nickname);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':rol', $rol);
            $stmt->bindParam(':actividad_economica', $actividad_economica);

            $stmt->execute();
            $success = "Registro guardado correctamente.";
        }
    } catch (PDOException $e) {
        $error = "Error en la base de datos: " . $e->getMessage();

        // Sugerencia si se detecta error por contraseña
        if (strpos($error, 'Access denied') !== false) {
            $error .= "<br>Verifica que el usuario 'root' tenga acceso sin contraseña y esté usando el plugin 'mysql_native_password'.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Crear empresa</title>
        <link rel="icon" href="../../img/Logotipo_Datasena.png" type="image/x-icon" />
        <link rel="stylesheet" href="css/registro_empresa.css" >
    </head>
    <body>
        <h1>DATASENA</h1>
        <img src="../../img/logo-sena.png" alt="Logo" class="img" />

        <div class="forma-container">
            <h3>Registro de Empresa</h3>

            <?php if (!empty($error)): ?>
                <div style="color: red; margin-bottom: 1em;"><?= $error ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div style="color: green; margin-bottom: 1em;"><?= $success ?></div>
            <?php endif; ?>

            <form action="empresaRe_su.php" method="POST">
                <div class="forma-grid">
                    <!-- Primera columna -->
                    <div>
                        <div class="forma-row">
                            <label for="tipo_documento">Tipo de Documento:</label>
                            <select id="tipo_documento" name="tipo_documento" required class="md-input">
                                <option value="">Seleccione una opción</option>
                                <option value="NIT">NIT</option>
                                <option value="CC">Cédula de Ciudadanía</option>
                                <option value="CE">Cédula de Extranjería</option>
                                <option value="Pasaporte">Pasaporte</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="forma-row">
                            <label for="nit">Número de documento:</label>
                            <input type="text" id="nit" name="nit" required class="md-input" value="<?= htmlspecialchars($_POST['nit'] ?? '') ?>">
                        </div>
                        <div class="forma-row">
                            <label for="nickname">Nombre de la empresa:</label>
                            <input type="text" id="nickname" name="nickname" required class="md-input" value="<?= htmlspecialchars($_POST['nickname'] ?? '') ?>">
                        </div>
                        <div class="forma-row">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" id="telefono" name="telefono" required class="md-input" value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Segunda columna -->
                    <div>
                        <div class="forma-row">
                            <label for="correo">Correo:</label>
                            <input type="email" id="correo" name="correo" required class="md-input" value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
                        </div>
                        <div class="forma-row">
                            <label for="direccion">Dirección:</label>
                            <input type="text" id="direccion" name="direccion" required class="md-input" value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>">
                        </div>
                        <div class="forma-row">
                            <label for="rol">Rol:</label>
                            <input type="text" id="rol" name="rol" required class="md-input" value="<?= htmlspecialchars($_POST['rol'] ?? '') ?>">
                        </div>
                        <div class="forma-row">
                            <label for="actividad_economica">Actividad Económica:</label>
                            <input type="text" id="actividad_economica" name="actividad_economica" required class="md-input" value="<?= htmlspecialchars($_POST['actividad_economica'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="buttons-container">
                    <button type="submit" class="logout-btn" onclick="window.location.href='crear_usuario_.php'">Crear</button>
                    <button type="button" class="logout-btn" onclick="window.location.href='../admin.menu.html'">Regresar</button>
                </div>
            </form>
        </div>

        <footer>
            <a>&copy; Todos los derechos reservados al SENA</a>
        </footer>
    </body>
</html>