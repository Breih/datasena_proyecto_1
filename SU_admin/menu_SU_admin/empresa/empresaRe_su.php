<?php
// Procesamiento del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = "localhost";
    $db = "datasenn_db";
    $user = "root";
    $pass = "";

    try {
        $conexion = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Recoger datos del formulario
        $tipo_documento = $_POST['tipo_documento'] ?? '';
        $numero_documento = $_POST['numero_documento'] ?? '';
        $nickname = $_POST['nickname'] ?? '';
        $numero_telefono = $_POST['numero_telefono'] ?? '';
        $correo_electronico = $_POST['correo_electronico'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $actividad_economica = $_POST['actividad_economica'] ?? '';
        $rol_id = $_POST['rol_id'] ?? null;
        $estado = 1;
        $confirmacion_correo = $correo_electronico;

        // Validaciones
        if (
            empty($tipo_documento) || empty($numero_documento) || empty($nickname) ||
            empty($numero_telefono) || empty($correo_electronico) || empty($direccion) ||
            empty($actividad_economica)
        ) {
            $error = "Por favor completa todos los campos obligatorios.";
        } elseif (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
            $error = "El correo electrónico no es válido.";
        } else {
            $sql = "INSERT INTO empresa (
                        tipo_documento, numero_documento, nickname, numero_telefono,
                        correo_electronico, confirmacion_correo, direccion,
                        actividad_economica, rol_id, estado
                    ) VALUES (
                        :tipo_documento, :numero_documento, :nickname, :numero_telefono,
                        :correo_electronico, :confirmacion_correo, :direccion,
                        :actividad_economica, :rol_id, :estado
                    )";

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':tipo_documento', $tipo_documento);
            $stmt->bindParam(':numero_documento', $numero_documento);
            $stmt->bindParam(':nickname', $nickname);
            $stmt->bindParam(':numero_telefono', $numero_telefono);
            $stmt->bindParam(':correo_electronico', $correo_electronico);
            $stmt->bindParam(':confirmacion_correo', $confirmacion_correo);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':actividad_economica', $actividad_economica);
            $stmt->bindParam(':rol_id', $rol_id);
            $stmt->bindParam(':estado', $estado);

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
    <link rel="icon" href="../../../img/Logotipo_Datasena.png" type="image/x-icon" />
    <link rel="stylesheet" href="../../../css/SU_admin/menu_SU_admin/empresa_registro.css" />
</head>
<body>
    <h1>DATASENA</h1>
    <img src="../../../img/logo-sena.png" alt="Logo" class="img" />

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
                <!-- Columna izquierda -->
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
                        <label for="numero_telefono">Teléfono:</label>
                        <input type="text" id="numero_telefono" name="numero_telefono" required value="<?= htmlspecialchars($_POST['numero_telefono'] ?? '') ?>">
                    </div>
                </div>

                <!-- Columna derecha -->
                <div>
                    <div class="forma-row">
                        <label for="correo_electronico">Correo:</label>
                        <input type="email" id="correo_electronico" name="correo_electronico" required value="<?= htmlspecialchars($_POST['correo_electronico'] ?? '') ?>">
                    </div>
                    <div class="forma-row">
                        <label for="direccion">Dirección:</label>
                        <input type="text" id="direccion" name="direccion" required value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>">
                    </div>
                    <div class="forma-row">
                        <label for="rol_id">Rol (ID):</label>
                        <input type="number" id="rol_id" name="rol_id" value="<?= htmlspecialchars($_POST['rol_id'] ?? '') ?>">
                    </div>
                    <div class="forma-row">
                        <label for="actividad_economica">Actividad Económica:</label>
                        <input type="text" id="actividad_economica" name="actividad_economica" required class="md-input" value="<?= htmlspecialchars($_POST['actividad_economica'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="logout-buttons-container">
                <button type="submit" class="logout-btn">Crear</button>
                <button type="button" class="logout-btn" onclick="window.location.href='../super_menu.html'">Regresar</button>
            </div>
        </form>
    </div>

    <footer>
        <a>&copy; Todos los derechos reservados al SENA</a>
    </footer>
</body>
</html>
