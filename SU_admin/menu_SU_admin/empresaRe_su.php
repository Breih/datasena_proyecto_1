<?php
// empresaRe_su.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexión a la base de datos
    $host = "localhost";
    $db = "datasenn_db";
    $user = "root";
    $pass = "";

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
        if (empty($tipo_documento) || empty($nit) || empty($nickname) || empty($telefono) || empty($correo) || empty($direccion) || empty($rol) || empty($actividad_economica)) {
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
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro de Empresa</title>
    <link rel="icon" href="../../img/Logotipo_Datasena.png" type="image/x-icon" />
    <link rel="stylesheet" href="../../css/SU_admin/menu_SU_admin/empresaRe_SU.css" />
</head>
<body>
    <h1>DATASENA</h1>
    <img src="../../img/logo-sena.png" alt="Logo" class="img" />

    <div class="forma-container">
        <h3>Registro de Empresa</h3>

        <?php if (!empty($error)): ?>
            <div style="color: red; margin-bottom: 1em;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div style="color: green; margin-bottom: 1em;"><?= htmlspecialchars($success) ?></div>
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
                        <label for="nit">Numero de documento:</label>
                        <input type="text" id="nit" name="nit" required class="md-input" value="<?= isset($_POST['nit']) ? htmlspecialchars($_POST['nit']) : '' ?>">
                    </div>
                    <div class="forma-row">
                        <label for="nickname">Nombre de la empresa:</label>
                        <input type="text" id="nickname" name="nickname" required class="md-input" value="<?= isset($_POST['nickname']) ? htmlspecialchars($_POST['nickname']) : '' ?>">
                    </div>
                    <div class="forma-row">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" id="telefono" name="telefono" required class="md-input" value="<?= isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : '' ?>">
                    </div>
                </div>

                <!-- Segunda columna -->
                <div>
                    <div class="forma-row">
                        <label for="correo">Correo:</label>
                        <input type="email" id="correo" name="correo" required class="md-input" value="<?= isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : '' ?>">
                    </div>
                    <div class="forma-row">
                        <label for="direccion">Dirección:</label>
                        <input type="text" id="direccion" name="direccion" required class="md-input" value="<?= isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : '' ?>">
                    </div>
                    <div class="forma-row">
                        <label for="rol">Rol:</label>
                        <input type="text" id="rol" name="rol" required class="md-input" value="<?= isset($_POST['rol']) ? htmlspecialchars($_POST['rol']) : '' ?>">
                    </div>
                    <div class="forma-row">
                        <label for="actividad_economica">Actividad Económica:</label>
                        <input type="text" id="actividad_economica" name="actividad_economica" required class="md-input" value="<?= isset($_POST['actividad_economica']) ? htmlspecialchars($_POST['actividad_economica']) : '' ?>">
                    </div>
                </div>
            </div>

            <div class="buttons-container">
                <button type="submit" class="back_crear">Registrar</button>
            </div>
        </form>
    </div>

    <footer>
        <a>&copy; Todos los derechos reservados al SENA</a>
    </footer>
</body>
</html>
