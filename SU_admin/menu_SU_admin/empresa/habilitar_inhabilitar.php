<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "datasenn_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Inicialización
$empresas = null;
$mensaje = "";
$mensaje_tipo = "";

// Actualización del estado de habilitación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_estado'])) {
    $cc = $_POST['cc'] ?? '';
    $nuevo_estado = $_POST['nuevo_estado'] ?? '';

    if (!empty($cc) && !empty($nuevo_estado)) {
        $stmt = $conexion->prepare("UPDATE empresas SET estado_habilitacion = ? WHERE numero_identidad = ?");
        if ($stmt) {
            $stmt->bind_param("ss", $nuevo_estado, $cc);
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $mensaje = "✅ Estado de la empresa actualizado correctamente.";
                $mensaje_tipo = "exito";
            } else {
                $mensaje = "⚠️ No se encontró la empresa o no hubo cambios.";
                $mensaje_tipo = "error";
            }
            $stmt->close();
        } else {
            $mensaje = "❌ Error en la preparación de la consulta: " . $conexion->error;
            $mensaje_tipo = "error";
        }
    }
}

// Búsqueda por número de documento
if (isset($_GET['cc'])) {
    $cc = $_GET['cc'];
    $stmt = $conexion->prepare(
        "SELECT tipo_documento, numero_identidad AS cc, nickname, telefono, 
         correo, direccion, actividad_economica, estado_habilitacion AS estado 
         FROM empresas WHERE numero_identidad = ?"
    );
    if ($stmt) {
        $stmt->bind_param("s", $cc);
        $stmt->execute();
        $result = $stmt->get_result();
        $empresas = $result->fetch_assoc();
        $stmt->close();
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilitar/Inhabilitar Empresas</title>
    <link rel="stylesheet" href="../../../css/SU_admin/menu_SU_admin/habilitar_in.css">
</head>
<body>
    <h1>DATASENA</h1>
    <img src="../../../img/logo-sena.png" alt="Logo SENA" class="img">

    <div class="forma-container">
        <h3>Habilitar/Inhabilitar Empresas</h3>

        <!-- Barra de búsqueda -->
        <div class="search-container">
            <h4>🔍 Buscar empresas</h4>
            <form method="get" class="search-form">
                <div class="search-group">
                    <label for="buscar_cc">Número de Identidad:</label>
                    <input type="text" id="buscar_cc" name="cc" required class="md-input">
                </div>
                <button type="submit" class="search-btn">Buscar Empresas</button>
            </form>
        </div>

        <!-- Mensajes -->
        <?php if (!empty($mensaje)) : ?>
            <div class="mensaje <?= $mensaje_tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <!-- Datos de la empresa -->
        <?php if ($empresas) : ?>
            <form method="post">
                <input type="hidden" name="cc" value="<?= htmlspecialchars($empresas['cc']) ?>">
                <div class="forma-grid">
                    <div>
                        <label>Tipo documento:</label>
                        <input type="text" value="<?= htmlspecialchars($empresas['tipo_documento']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Documento:</label>
                        <input type="text" value="<?= htmlspecialchars($empresas['cc']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Nombre empresa:</label>
                        <input type="text" value="<?= htmlspecialchars($empresas['nickname']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Teléfono:</label>
                        <input type="text" value="<?= htmlspecialchars($empresas['telefono']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Correo:</label>
                        <input type="text" value="<?= htmlspecialchars($empresas['correo']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Dirección:</label>
                        <input type="text" value="<?= htmlspecialchars($empresas['direccion']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Actividad económica:</label>
                        <input type="text" value="<?= htmlspecialchars($empresas['actividad_economica']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Estado actual:</label>
                        <input type="text" value="<?= $empresas['estado'] === 'Activo' ? 'Habilitado' : 'Inhabilitado' ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label for="nuevo_estado">Cambiar estado a:</label>
                        <select name="nuevo_estado" id="nuevo_estado" required class="md-input">
                            <option value="">-- Seleccione nuevo estado --</option>
                            <option value="Activo" <?= $empresas['estado'] === 'Inactivo' ? 'selected' : '' ?>>✅ Habilitar</option>
                            <option value="Inactivo" <?= $empresas['estado'] === 'Activo' ? 'selected' : '' ?>>❌ Inhabilitar</option>
                        </select>
                    </div>
                </div>

                <div class="buttons-container">
                    <button type="submit" name="actualizar_estado" class="back">Actualizar Estado</button>
                    <button type="button" class="habilitar-btn" onclick="window.location.href='../super_menu.html'">Regresar al Menú</button>
                </div>
            </form>
        <?php elseif (isset($_GET['cc'])): ?>
            <div class="user-not-found">
                <h4>❌ Empresa no encontrada</h4>
                <p>Verifique el documento e intente nuevamente.</p>
            </div>
        <?php else: ?>
            <div class="user-not-found">
                <h4>👇 Ingrese un número de documento para buscar una empresa.</h4>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        &copy; Todos los derechos reservados al SENA
    </footer>
</body>
</html>
