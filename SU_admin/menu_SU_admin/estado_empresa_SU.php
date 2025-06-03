<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "123456", "datasenn_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Inicializar datos de la empresa con campos vacíos
$empresa = [
    'nickname' => '',
    'nit' => '',
    'direccion' => '',
    'correo' => '',
    'telefono' => '',
    'estado' => ''
];

$mensaje = "";
$mensaje_tipo = "";
$empresa_encontrada = false;

// Si se envió el formulario para actualizar estado (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_estado'])) {
    $nit = $_POST['nit'];
    $nuevo_estado = $_POST['nuevo_estado'];

    if (!empty($nit) && !empty($nuevo_estado)) {
        $stmt = $conexion->prepare("UPDATE empresas SET estado = ? WHERE nit = ?");
        $stmt->bind_param("ss", $nuevo_estado, $nit);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $mensaje = "Estado de la empresa actualizado correctamente.";
                $mensaje_tipo = "exito";
            } else {
                $mensaje = "No se encontró la empresa o no se realizaron cambios.";
                $mensaje_tipo = "error";
            }
        } else {
            $mensaje = "Error al actualizar el estado de la empresa.";
            $mensaje_tipo = "error";
        }
        $stmt->close();
    }
}

// Si se envió búsqueda por NIT (GET o POST)
if (isset($_GET['nit']) || isset($_POST['buscar_empresa'])) {
    $nit = $_GET['nit'] ?? $_POST['nit'] ?? '';
    
    if (!empty($nit)) {
        $stmt = $conexion->prepare("SELECT tipo_documento, nit, nickname, telefono, correo, direccion, rol, actividad_economica, estado FROM empresas WHERE nit = ?");
        $stmt->bind_param("s", $nit);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $empresa = $resultado->fetch_assoc();
            $empresa_encontrada = true;
        } else {
            $mensaje = "No se encontró ninguna empresa con ese NIT.";
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
    <title>Habilitar Inhabilitar Empresa</title>
    <link rel="icon" href="../../img/Logotipo_Datasena.png" type="image/x-icon" />
    <link rel="stylesheet" href="../../css/SU_admin/menu_SU_admin/habilitar_inhabilitar_SU.css" />
</head>
<body>
    <h1>DATASENA</h1>
    <img src="../../img/logo-sena.png" alt="Logo SENA" class="img" />

    <div class="forma-container">
        <h3>Habilitar / Inhabilitar Empresa</h3>
        
        <!-- Barra de búsqueda -->
        <div class="search-container">
            <h4>🔍 Buscar Empresa</h4>
            <form class="search-form" action="" method="get">
                <div class="search-group">
                    <label for="buscar_nit">NIT:</label>
                    <input
                        type="text"
                        id="buscar_nit"
                        name="nit"
                        placeholder="Ingrese el NIT de la empresa"
                        value="<?= htmlspecialchars($_GET['nit'] ?? '') ?>"
                        required
                    />
                </div>
                <button type="submit" class="search-btn">Buscar Empresa</button>
            </form>
        </div>

        <!-- Mensajes de estado -->
        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?= htmlspecialchars($mensaje_tipo) ?>">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <?php if ($empresa_encontrada): ?>
            <!-- Formulario datos empresa encontrada -->
            <form action="" method="post">
                <input type="hidden" name="nit" value="<?= htmlspecialchars($empresa['nit']) ?>" />
                
                <div class="forma-grid">
                    <div>
                        <div class="forma-row">
                            <label for="nombre_empresa">Nombre Empresa:</label>
                            <input
                                type="text"
                                id="nombre_empresa"
                                name="nombre_empresa"
                                value="<?= htmlspecialchars($empresa['nickname']) ?>"
                                readonly
                                class="md-input"
                            />
                        </div>

                        <div class="forma-row">
                            <label for="nit_field">NIT:</label>
                            <input
                                type="text"
                                id="nit_field"
                                name="nit_field"
                                value="<?= htmlspecialchars($empresa['nit']) ?>"
                                readonly
                                class="md-input"
                            />
                        </div>

                        <div class="forma-row">
                            <label for="direccion">Dirección:</label>
                            <input
                                type="text"
                                id="direccion"
                                name="direccion"
                                value="<?= htmlspecialchars($empresa['direccion']) ?>"
                                readonly
                                class="md-input"
                            />
                        </div>
                    </div>

                    <div>
                        <div class="forma-row">
                            <label for="correo">Correo:</label>
                            <input
                                type="email"
                                id="correo"
                                name="correo"
                                value="<?= htmlspecialchars($empresa['correo']) ?>"
                                readonly
                                class="md-input"
                            />
                        </div>

                        <div class="forma-row">
                            <label for="telefono">Teléfono:</label>
                            <input
                                type="text"
                                id="telefono"
                                name="telefono"
                                value="<?= htmlspecialchars($empresa['telefono']) ?>"
                                readonly
                                class="md-input"
                            />
                        </div>

                        <div class="forma-row">
                            <label for="estado_actual">Estado actual:</label>
                            <input
                                type="text"
                                id="estado_actual"
                                name="estado_actual"
                                value="<?= htmlspecialchars($empresa['estado']) ?>"
                                readonly
                                class="md-input"
                            />
                        </div>

                        <div class="forma-row">
                            <label for="nuevo_estado">Cambiar estado a:</label>
                            <select name="nuevo_estado" id="nuevo_estado" required class="md-input">
                                <option value="">-- Seleccione nuevo estado --</option>
                                <option value="Activo" <?= ($empresa['estado'] === 'Inactivo' || $empresa['estado'] === 'Inhabilitado') ? 'selected' : '' ?>>
                                    ✅ Habilitar Empresa
                                </option>
                                <option value="Inactivo" <?= ($empresa['estado'] === 'Activo' || $empresa['estado'] === 'Habilitado') ? 'selected' : '' ?>>
                                    ❌ Inhabilitar Empresa
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="buttons-container">
                    <button type="submit" name="actualizar_estado" class="back">Actualizar Estado</button>
                    <button type="button" class="habilitar-btn" onclick="window.location.href='super.menu.html'">Regresar al Menú</button>
                </div>
            </form>

        <?php elseif (isset($_GET['nit']) && !empty($_GET['nit']) && !$empresa_encontrada): ?>
            <!-- Mensaje cuando no se encuentra empresa -->
            <div class="user-not-found">
                <h4>❌ Empresa no encontrada</h4>
                <p>No se encontró ninguna empresa con el NIT: <strong><?= htmlspecialchars($_GET['nit']) ?></strong></p>
                <p>Por favor, verifique el NIT e intente nuevamente.</p>
            </div>
            
            <div class="buttons-container">
                <button type="button" class="habilitar-btn" onclick="window.location.href='super.menu.html'">Regresar al Menú</button>
            </div>

        <?php else: ?>
            <!-- Estado inicial - sin búsqueda -->
            <div class="user-not-found">
                <h4>👆 Ingrese un NIT para buscar</h4>
                <p>Use la barra de búsqueda de arriba para encontrar la empresa que desea habilitar o inhabilitar.</p>
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
