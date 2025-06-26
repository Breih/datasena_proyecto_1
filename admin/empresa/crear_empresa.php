<?php
// empresaRe_su.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = "localhost";
    $db = "datasenn_db";
    $user = "root";
    $pass = "";

    try {
        $conexion = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Recoger y limpiar datos del formulario
        $tipo_documento = trim($_POST['tipo_documento'] ?? '');
        $nit = trim($_POST['nit'] ?? '');
        $nickname = trim($_POST['nickname'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $rol = trim($_POST['rol'] ?? '');
        $actividad_economica = trim($_POST['actividad_economica'] ?? '');

        // Validaciones
        $errores = [];

        // Validar tipo de documento
        $tipos_validos = ['NIT', 'CC', 'CE', 'Pasaporte', 'Otro'];
        if (!in_array($tipo_documento, $tipos_validos)) {
            $errores[] = "❌ - Tipo de documento inválido.";
        }

        // Validar NIT / documento (solo números, longitud razonable)
        if (!preg_match('/^\d{5,20}$/', $nit)) {
            $errores[] = "❌ - El número de documento debe contener solo números (entre 5 y 20 dígitos).";
        }

        // Validar nombre de empresa (letras, números, espacios)
        if (!preg_match('/^[\p{L}\p{N} ]{2,100}$/u', $nickname)) {
            $errores[] = "❌ - El nombre de la empresa contiene caracteres inválidos.";
        }

        // Validar teléfono (solo números, opcionalmente con + y espacios)
        if (!preg_match('/^\+?\d{7,15}$/', $telefono)) {
            $errores[] = "❌ - El teléfono no tiene un formato válido.";
        }

        // Validar correo electrónico
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "❌ - El correo no es válido.";
        }

        // Validar dirección (longitud y caracteres válidos)
        if (strlen($direccion) < 5 || strlen($direccion) > 100) {
            $errores[] = "❌ - La dirección debe tener entre 5 y 100 caracteres.";
        }

        // Validar rol (solo letras y espacios)
        if (!preg_match('/^[\p{L} ]{2,50}$/u', $rol)) {
            $errores[] = "❌ - El rol contiene caracteres inválidos.";
        }

        // Validar actividad económica (letras, números y espacios)
        if (!preg_match('/^[\p{L}\p{N} ]{2,100}$/u', $actividad_economica)) {
            $errores[] = "❌ - La actividad económica contiene caracteres inválidos.";
        }

        // Procesar si no hay errores
        if (empty($errores)) {
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
        } else {
            $error = implode("<br>", $errores);
        }
    } catch (PDOException $e) {
        $error = "Error en la base de datos: " . $e->getMessage();
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
        <div class="blue-line-top">
            <img src="../../img/gov.png" alt="gov" class="gov-logo">
        </div>

        <h2>DATASENA</h2>
        <img src="../../img/logo-sena.png" alt="Logo" class="logo-sena" />

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
                            <label for="tipo_documento">Tipo de<br> Documento:</label>
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
                            <label for="nit">Número de <br>documento:</label>
                            <input type="text" id="nit" name="nit" placeholder="Ingrese el número de documento" required class="md-input" value="<?= htmlspecialchars($_POST['nit'] ?? '') ?>">
                        </div>
                        <div class="forma-row">
                            <label for="nickname">Nombre de <br>la empresa:</label>
                            <input type="text" id="nickname" name="nickname" placeholder="Ingrese el nombre de la empresa" required class="md-input" value="<?= htmlspecialchars($_POST['nickname'] ?? '') ?>">
                        </div>
                        <div class="forma-row">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" id="telefono" name="telefono" placeholder="Ingrese su número de telefono" required class="md-input" value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Segunda columna -->
                    <div>
                        <div class="forma-row">
                            <label for="correo">Correo:</label>
                            <input type="email" id="correo" name="correo" placeholder="Ingrese su correo electronico" required class="md-input" value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
                        </div>
                        <div class="forma-row">
                            <label for="direccion">Dirección:</label>
                            <input type="text" id="direccion" name="direccion" placeholder="Ingrese su direccion" required class="md-input" value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>">
                        </div>
                        <div class="forma-row">
                            <label for="rol">Rol:</label>
                            <input type="text" id="rol" name="rol" placeholder="Ingrese su rol" required class="md-input" value="<?= htmlspecialchars($_POST['rol'] ?? '') ?>">
                        </div>
                        <div class="forma-row">
                            <label for="actividad_economica">Actividad<br> Económica:</label>
                            <input type="text" id="actividad_economica" name="actividad_economica" placeholder="Ingrese su actividad economica" required class="md-input" value="<?= htmlspecialchars($_POST['actividad_economica'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="logout-buttons-container">
                    <button type="submit" class="logout-btn" onclick="window.location.href='crear_usuario_.php'">Crear</button>
                    <button type="button" class="logout-btn" onclick="window.location.href='../admin.menu.html'">Regresar</button>
                </div>
            </form>
        </div>

        <div class="blue-line-bottom">
            <img class="gov-logo" src="../../img/gov.png" alt="gov">
        </div>

        <footer>
            <a>&copy; Todos los derechos reservados al SENA</a>
        </footer>
    </body>
</html>