<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "123456", "datasenn_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Inicialización
$empresa = null;
$mensaje = "";
$mensaje_tipo = "";

// Actualización del estado_habilitacion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_estado'])) {
    $nit = $_POST['nit'];
    $nuevo_estado = $_POST['nuevo_estado'];

    if (!empty($nit) && !empty($nuevo_estado)) {
        $stmt = $conexion->prepare(
            "UPDATE empresas SET estado_habilitacion = ? WHERE nit = ?"
        );
        $stmt->bind_param("si", $nuevo_estado, $nit);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $mensaje = "Estado de la empresa actualizado correctamente.";
            $mensaje_tipo = "exito";
        } else {
            $mensaje = "No se encontró la empresa o no hubo cambios.";
            $mensaje_tipo = "error";
        }
        $stmt->close();
    }
}

// Búsqueda por NIT
if (isset($_GET['nit'])) {
    $nit = $_GET['nit'];
    $stmt = $conexion->prepare(
        "SELECT tipo_documento, nit, nickname, telefono, correo, direccion, rol, actividad_economica, estado_habilitacion
         FROM empresas WHERE nit = ?"
    );
    $stmt->bind_param("i", $nit);
    $stmt->execute();
    $result = $stmt->get_result();
    $empresa = $result->fetch_assoc();
    $stmt->close();
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Habilitar/Inhabilitar Empresa</title>
<link rel="stylesheet" href="../../../css/SU_admin/menu_SU_admin/habilitar_in.css">
</head>
<body>
<h1>DATASENA</h1>
<img src="../../../img/logo-sena.png" alt="Logo SENA" class="img">

<div class="forma-container">
    <h3>Habilitar/Inhabilitar Empresa</h3>

    <!-- Barra de búsqueda -->
    <div class="search-container">
        <h4>🔍 Buscar Empresa</h4>
        <form method="get" class="search-form">
            <div class="search-group">
                <label for="buscar_nit">Número de NIT:</label>
                <input type="text" id="buscar_nit" name="nit" required class="md-input">
            </div>
            <button type="submit" class="search-btn">Buscar Empresa</button>
        </form>
    </div>

    <!-- Mensajes -->
    <?php if (!empty($mensaje)) : ?>
        <div class="mensaje <?= $mensaje_tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <!-- Datos de la empresa -->
    <?php if ($empresa) : ?>
        <form method="post">
            <input type="hidden" name="nit" value="<?= htmlspecialchars($empresa['nit']) ?>">
            <div class="forma-grid">
                <div>
                    <label>Tipo documento:</label>
                    <input type="text" value="<?= htmlspecialchars($empresa['tipo_documento']) ?>" readonly class="md-input" />
                </div>
                <div>
                    <label>NIT:</label>
                    <input type="text" value="<?= htmlspecialchars($empresa['nit']) ?>" readonly class="md-input" />
                </div>
                <div>
                    <label>Nombre empresa:</label>
                    <input type="text" value="<?= htmlspecialchars($empresa['nickname']) ?>" readonly class="md-input" />
                </div>
                <div>
                    <label>Teléfono:</label>
                    <input type="text" value="<?= htmlspecialchars($empresa['telefono']) ?>" readonly class="md-input" />
                </div>
                <div>
                    <label>Correo:</label>
                    <input type="text" value="<?= htmlspecialchars($empresa['correo']) ?>" readonly class="md-input" />
                </div>
                <div>
                    <label>Dirección:</label>
                    <input type="text" value="<?= htmlspecialchars($empresa['direccion']) ?>" readonly class="md-input" />
                </div>
                <div>
                    <label>Actividad económica:</label>
                    <input type="text" value="<?= htmlspecialchars($empresa['actividad_economica']) ?>" readonly class="md-input" />
                </div>
                <div>
                    <label>Estado actual:</label>
                    <input type="text" value="<?= $empresa['estado_habilitacion']==='Activo' ? 'Habilitado' : 'Inhabilitado' ?>" readonly class="md-input" />
                </div>
                <div>
                    <label for="nuevo_estado">Cambiar estado a:</label>
                    <select name="nuevo_estado" id="nuevo_estado" required class="md-input">
                        <option value="">-- Seleccione nuevo estado --</option>
                        <option value="Activo" <?= $empresa['estado_habilitacion']==='Inactivo' ? 'selected' : '' ?>>✅ Habilitar</option>
                        <option value="Inactivo" <?= $empresa['estado_habilitacion']==='Activo' ? 'selected' : '' ?>>❌ Inhabilitar</option>
                    </select>
                </div>
            </div>

            <div class="buttons-container">
                <button type="submit" name="actualizar_estado" class="back">Actualizar Estado</button>
                <button type="button" class="habilitar-btn" onclick="window.location.href='../super_menu.html'">Regresar al Menú</button>
            </div>
        </form>
    <?php elseif (isset($_GET['nit'])): ?>
        <div class="user-not-found">
            <h4>❌ Empresa no encontrada</h4>
            <p>Verifique el NIT e intente nuevamente.</p>
        </div>
    <?php else: ?>
        <div class="user-not-found">
            <h4>👆 Ingrese un NIT para buscar una empresa.</h4>
        </div>
    <?php endif; ?>
</div>

<footer>
    &copy; Todos los derechos reservados al SENA
</footer>
</body>
</html>
