<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "datasenn_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Inicializar datos del admin
$admin = [
    'id' => '',
    'nombres' => '',
    'numero_documento' => '',
    'correo_electronico' => '',
    'nickname' => '',
    'estado' => ''
];

$mensaje = "";
$mensaje_tipo = "";
$admin_encontrado = false;

// Actualizar estado si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_estado'])) {
    $numero_documento = $_POST['numero_documento'] ?? '';
    $nuevo_estado = $_POST['nuevo_estado'] ?? '';

    if (!empty($numero_documento) && !empty($nuevo_estado)) {
        $stmt = $conexion->prepare("UPDATE admin SET estado = ? WHERE numero_documento = ?");
        $stmt->bind_param("ss", $nuevo_estado, $numero_documento);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $mensaje = "Estado del administrador actualizado correctamente.";
                $mensaje_tipo = "exito";
            } else {
                $mensaje = "No se encontró el administrador o no se realizaron cambios.";
                $mensaje_tipo = "error";
            }
        } else {
            $mensaje = "Error al actualizar el estado del administrador.";
            $mensaje_tipo = "error";
        }
        $stmt->close();
    } else {
        $mensaje = "Faltan datos para actualizar el estado.";
        $mensaje_tipo = "error";
    }
}

// Buscar admin por numero_documento si se envía búsqueda
if (isset($_GET['numero_documento']) || isset($_POST['buscar_admin'])) {
    $numero_documento = $_GET['numero_documento'] ?? $_POST['numero_documento'] ?? '';
    
    if (!empty($numero_documento)) {
        $stmt = $conexion->prepare("SELECT id, nombres, apellidos, numero_documento, correo_electronico, nickname, estado FROM admin WHERE numero_documento = ?");
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
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Habilitar / Inhabilitar Administrador</title>
    <link rel="stylesheet" href="../../css/SU_admin/menu_SU_admin/habilitar_in_SU.css" />
</head>
<body>
    <h1>DATASENA - Administración de Administradores</h1>
    <img src="../../img/logo-sena.png" alt="Logo SENA" class="img">

    <div class="forma-container">
        <h3>Habilitar / Inhabilitar Administrador</h3>

        <div class="search-container">
            <h4>🔍 Buscar Administrador</h4>
            <form class="search-form" method="get" action="">
                <label for="buscar_documento">Número de Documento:</label>
                <input type="text" 
                       id="buscar_documento" 
                       name="numero_documento" 
                       placeholder="Ingrese número de documento" 
                       value="<?= htmlspecialchars($_GET['numero_documento'] ?? '') ?>" 
                       required>
                <button type="submit" class="search-btn">Buscar Administrador</button>
            </form>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?= $mensaje_tipo ?>">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <?php if ($admin_encontrado): ?>
            <form method="post" action="">
                <input type="hidden" name="numero_documento" value="<?= htmlspecialchars($admin['numero_documento']) ?>" />

                <div class="forma-grid">
                    <div>
                        <label>Nombres completos:</label>
                        <input type="text" value="<?= htmlspecialchars($admin['nombres'] . ' ' . $admin['apellidos']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Documento:</label>
                        <input type="text" value="<?= htmlspecialchars($admin['numero_documento']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Correo electrónico:</label>
                        <input type="email" value="<?= htmlspecialchars($admin['correo_electronico']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Nickname:</label>
                        <input type="text" value="<?= htmlspecialchars($admin['nickname']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Estado actual:</label>
                        <input type="text" value="<?= htmlspecialchars($admin['estado'] ?? 'Desconocido') ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label for="nuevo_estado">Cambiar estado a:</label>
                        <select name="nuevo_estado" id="nuevo_estado" required class="md-input">
                            <option value="">-- Seleccione nuevo estado --</option>
                            <option value="Activo" <?= (isset($admin['estado']) && ($admin['estado'] === 'Inactivo' || $admin['estado'] === 'Inhabilitado')) ? 'selected' : '' ?>>✅ Habilitar Administrador</option>
                            <option value="Inactivo" <?= (isset($admin['estado']) && ($admin['estado'] === 'Activo' || $admin['estado'] === 'Habilitado')) ? 'selected' : '' ?>>❌ Inhabilitar Administrador</option>
                        </select>
                    </div>
                </div>

                <div class="buttons-container">
                    <button type="submit" name="actualizar_estado" class="back">Actualizar Estado</button>
                    <button type="button" class="habilitar-btn" onclick="window.location.href='super.menu.html'">Regresar al Menú</button>
                </div>
            </form>

        <?php elseif (isset($_GET['numero_documento']) && !empty($_GET['numero_documento']) && !$admin_encontrado): ?>
            <div class="user-not-found">
                <h4>❌ Administrador no encontrado</h4>
                <p>No se encontró ningún administrador con el número de documento: 
                   <strong><?= htmlspecialchars($_GET['numero_documento']) ?></strong>
                </p>
                <p>Por favor, verifique el número e intente nuevamente.</p>
            </div>
            <div class="buttons-container">
                <button type="button" class="habilitar-btn" onclick="window.location.href='super.menu.html'">Regresar al Menú</button>
            </div>

        <?php else: ?>
            <div class="user-not-found">
                <h4>👆 Ingrese un número de documento para buscar</h4>
                <p>Use la barra de búsqueda de arriba para encontrar el administrador que desea habilitar o inhabilitar.</p>
            </div>
            <div class="buttons-container">
                <button type="button" class="habilitar-btn" onclick="window.location.href='super.menu.html'">Regresar al Menú</button>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <a>&copy; Todos los derechos reservados al SENA</a>
    </footer>
</body>
</html>
