<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "datasenn_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Inicializar datos del programa
$programa = [
    'id' => '',
    'nombre_programa' => '',
    'tipo_programa' => '',
    'numero_ficha' => '',
    'duracion_programa' => '',
    'activacion' => ''
];

$mensaje = "";
$mensaje_tipo = "";
$programa_encontrado = false;

// Actualizar estado si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_estado'])) {
    $numero_ficha = $_POST['numero_ficha'] ?? '';
    $nuevo_estado = $_POST['nuevo_estado'] ?? '';

    if (!empty($numero_ficha) && !empty($nuevo_estado)) {
        $stmt = $conexion->prepare("UPDATE programas SET activacion = ? WHERE numero_ficha = ?");
        $stmt->bind_param("ss", $nuevo_estado, $numero_ficha);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $mensaje = "Estado del programa actualizado correctamente.";
                $mensaje_tipo = "exito";
            } else {
                $mensaje = "No se encontró el programa o no se realizaron cambios.";
                $mensaje_tipo = "error";
            }
        } else {
            $mensaje = "Error al actualizar el estado del programa.";
            $mensaje_tipo = "error";
        }
        $stmt->close();
    } else {
        $mensaje = "Faltan datos para actualizar el estado.";
        $mensaje_tipo = "error";
    }
}

// Buscar programa por numero_ficha si se envía búsqueda
if (isset($_GET['numero_ficha']) || isset($_POST['buscar_programa'])) {
    $numero_ficha = $_GET['numero_ficha'] ?? $_POST['numero_ficha'] ?? '';
    
    if (!empty($numero_ficha)) {
        $stmt = $conexion->prepare("SELECT id, nombre_programa, tipo_programa, numero_ficha, duracion_programa, activacion FROM programas WHERE numero_ficha = ?");
        $stmt->bind_param("s", $numero_ficha);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $programa = $resultado->fetch_assoc();
            $programa_encontrado = true;
        } else {
            $mensaje = "No se encontró ningún programa con ese número de ficha.";
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
    <title>Habilitar / Inhabilitar Programa</title>
    <link rel="stylesheet" href="../../../css/SU_admin/menu_SU_admin/habilitar_in.css" />
</head>
<body>
    <h1>DATASENA - Administración de Programas</h1>
    <img src="../../img/logo-sena.png" alt="Logo SENA" class="img">

    <div class="forma-container">
        <h3>Habilitar / Inhabilitar Programa</h3>

        <div class="search-container">
            <h4>🔍 Buscar Programa</h4>
            <form class="search-form" method="get" action="">
                <label for="buscar_ficha">Número de Ficha:</label>
                <input type="text" 
                       id="buscar_ficha" 
                       name="numero_ficha" 
                       placeholder="Ingrese número de ficha" 
                       value="<?= htmlspecialchars($_GET['numero_ficha'] ?? '') ?>" 
                       required>
                <button type="submit" class="search-btn" name="buscar_programa">Buscar Programa</button>
            </form>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?= $mensaje_tipo ?>">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <?php if ($programa_encontrado): ?>
            <form method="post" action="">
                <input type="hidden" name="numero_ficha" value="<?= htmlspecialchars($programa['numero_ficha']) ?>" />

                <div class="forma-grid">
                    <div>
                        <label>Nombre del Programa:</label>
                        <input type="text" value="<?= htmlspecialchars($programa['nombre_programa']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Tipo de Programa:</label>
                        <input type="text" value="<?= htmlspecialchars($programa['tipo_programa']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Número de Ficha:</label>
                        <input type="text" value="<?= htmlspecialchars($programa['numero_ficha']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Duración:</label>
                        <input type="text" value="<?= htmlspecialchars($programa['duracion_programa']) ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label>Estado Actual:</label>
                        <input type="text" value="<?= htmlspecialchars($programa['activacion'] ?? 'Desconocido') ?>" readonly class="md-input" />
                    </div>
                    <div>
                        <label for="nuevo_estado">Cambiar estado a:</label>
                        <select name="nuevo_estado" id="nuevo_estado" required class="md-input">
                            <option value="">-- Seleccione nuevo estado --</option>
                            <option value="Activo" <?= (isset($programa['activacion']) && ($programa['activacion'] === 'Inactivo' || $programa['activacion'] === 'Inhabilitado')) ? 'selected' : '' ?>>✅ Habilitar Programa</option>
                            <option value="Inactivo" <?= (isset($programa['activacion']) && ($programa['activacion'] === 'Activo' || $programa['activacion'] === 'Habilitado')) ? 'selected' : '' ?>>❌ Inhabilitar Programa</option>
                        </select>
                    </div>
                </div>

                <div class="buttons-container">
                    <button type="submit" name="actualizar_estado" class="back">Actualizar Estado</button>
                    <button type="button" class="habilitar-btn" onclick="window.location.href='super.menu.html'">Regresar al Menú</button>
                </div>
            </form>

        <?php elseif (isset($_GET['numero_ficha']) && !empty($_GET['numero_ficha']) && !$programa_encontrado): ?>
            <div class="user-not-found">
                <h4>❌ Programa no encontrado</h4>
                <p>No se encontró ningún programa con el número de ficha: 
                   <strong><?= htmlspecialchars($_GET['numero_ficha']) ?></strong>
                </p>
                <p>Por favor, verifique el número e intente nuevamente.</p>
            </div>
            <div class="buttons-container">
                <button type="button" class="habilitar-btn" onclick="window.location.href='super.menu.html'">Regresar al Menú</button>
            </div>

        <?php else: ?>
            <div class="user-not-found">
                <h4>👆 Ingrese un número de ficha para buscar</h4>
                <p>Use la barra de búsqueda de arriba para encontrar el programa que desea habilitar o inhabilitar.</p>
            </div>
            <div class="buttons-container">
                <button type="button" class="habilitar-btn" onclick="window.location.href='../menu_SU_admin/super.menu.html'">Regresar al Menú</button>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <a>&copy; Todos los derechos reservados al SENA</a>
    </footer>
</body>
</html>
