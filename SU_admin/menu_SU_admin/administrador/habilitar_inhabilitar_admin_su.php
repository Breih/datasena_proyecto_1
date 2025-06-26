<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "datasenn_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Inicialización
$admin = null;
$mensaje = "";
$mensaje_tipo = "";
$admin_encontrado = false;

// Actualización del estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_estado'])) {
    $numero_documento = $_POST['numero_documento'];
    $nuevo_estado = $_POST['nuevo_estado'];

    if (!empty($numero_documento) && !empty($nuevo_estado)) {
        $stmt = $conexion->prepare(
            "UPDATE admin SET estado_habilitacion = ? WHERE numero_documento = ?"
        );
        $stmt->bind_param("ss", $nuevo_estado, $numero_documento);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $mensaje = "Estado del administrador actualizado correctamente.";
            $mensaje_tipo = "exito";
        } else {
            $mensaje = "No se encontró el administrador o no hubo cambios.";
            $mensaje_tipo = "error";
        }
        $stmt->close();
    }
}

// Búsqueda por documento
if (isset($_GET['numero_documento'])) {
    $numero_documento = $_GET['numero_documento'];
    $stmt = $conexion->prepare(
        "SELECT id, nombres, apellidos, numero_documento, correo_electronico, nickname, estado_habilitacion 
         FROM admin WHERE numero_documento = ?"
    );
    $stmt->bind_param("s", $numero_documento);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $admin = $resultado->fetch_assoc();
        $admin_encontrado = true;
    } else {
        $mensaje = "No se encontró ningún administrador con ese número de documento.";
        $mensaje_tipo = "error";
    }
    $stmt->close();
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Habilitar/Inhabilitar Administrador</title>
<link rel="stylesheet" href="../../../css/SU_admin/menu_SU_admin/habilitar_in.css">
</head>
<body>
<h1>DATASENA</h1>
<img src="../../../img/logo-sena.png" alt="Logo SENA" class="img">

<div class="forma-container">
    <h3>Habilitar/Inhabilitar Administrador</h3>

    <!-- Barra de búsqueda -->
    <div class="search-container">
        <h4>🔍 Buscar Administrador</h4>
        <form method="get" class="search-form">
            <div class="search-group">
                <label for="buscar_documento">Número de Documento:</label>
                <input type="text" id="buscar_documento" name="numero_documento" required class="md-input">
            </div>
            <button type="submit" class="search-btn">Buscar Administrador</button>
        </form>
    </div>

    <!-- Mensaje -->
    <?php if (!empty($mensaje)) : ?>
        <div class="mensaje <?= $mensaje_tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <!-- Formulario del admin -->
    <?php if ($admin_encontrado): ?>
        <form method="post">
            <input type="hidden" name="numero_documento" value="<?= htmlspecialchars($admin['numero_documento']) ?>">
            <div class="forma-grid">

                <div>
                    <label>Nombres completos:</label>
                    <input type="text" value="<?= htmlspecialchars($admin['nombres'] . ' ' . $admin['apellidos']) ?>" readonly class="md-input">
                </div>
                <div>
                    <label>Número de documento:</label>
                    <input type="text" value="<?= htmlspecialchars($admin['numero_documento']) ?>" readonly class="md-input">
                </div>
                <div>
                    <label>Correo electrónico:</label>
                    <input type="text" value="<?= htmlspecialchars($admin['correo_electronico']) ?>" readonly class="md-input">
                </div>
                <div>
                    <label>Nickname:</label>
                    <input type="text" value="<?= htmlspecialchars($admin['nickname']) ?>" readonly class="md-input">
                </div>
                <div>
                    <label>Estado actual:</label>
                    <input type="text" value="<?= $admin['estado_habilitacion']==='Activo' ? 'Habilitado' : 'Inhabilitado' ?>" readonly class="md-input">
                </div>
                <div>
                    <label for="nuevo_estado">Cambiar estado a:</label>
                    <select name="nuevo_estado" id="nuevo_estado" class="md-input" required>
                        <option value="">-- Seleccione nuevo estado --</option>
                        <option value="Activo" <?= $admin['estado_habilitacion']==='Inactivo' ? 'selected' : '' ?>>✅ Habilitar</option>
                        <option value="Inactivo" <?= $admin['estado_habilitacion']==='Activo' ? 'selected' : '' ?>>❌ Inhabilitar</option>
                    </select>
                </div>

            </div>

            <div class="buttons-container">
                <button type="submit" name="actualizar_estado" class="back">Actualizar Estado</button>
                <button type="button" class="habilitar-btn" onclick="window.location.href='../super_menu.html'">Regresar al Menú</button>
            </div>
        </form>

    <?php elseif (isset($_GET['numero_documento'])): ?>
        <div class="user-not-found">
            <h4>❌ Administrador no encontrado</h4>
            <p>Verifique el número e intente nuevamente.</p>
        </div>
    <?php else: ?>
        <div class="user-not-found">
            <h4>👆 Ingrese un número de documento para buscar.</h4>
        </div>
    <?php endif; ?>
</div>

<footer>
    &copy; Todos los derechos reservados al SENA
</footer>
</body>
</html>
