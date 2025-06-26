<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "datasenn_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Inicializar datos del usuario
$usuario = [
    'nombre_completo' => '',
    'tipo_documento' => '',
    'numero_identidad' => '',
    'residencia' => '',
    'tipo_sangre' => '',
    'correo' => '',
    'telefono' => '',
    'estado_habilitacion' => '' // NUEVO campo
];

$mensaje = "";
$usuario_encontrado = false;

// Si se envió el formulario para actualizar estado (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_estado'])) {
    $numero_identidad = $_POST['numero_identidad'];
    $nuevo_estado = $_POST['nuevo_estado'];

    if (!empty($numero_identidad) && !empty($nuevo_estado)) {
        $stmt = $conexion->prepare(
            "UPDATE usuarios SET estado_habilitacion = ? WHERE numero_identidad = ?"
        );
        $stmt->bind_param("ss", $nuevo_estado, $numero_identidad);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $mensaje = "Estado del usuario actualizado correctamente.";
                $mensaje_tipo = "exito";
            } else {
                $mensaje = "No se encontró el usuario o no se realizaron cambios.";
                $mensaje_tipo = "error";
            }
        } else {
            $mensaje = "Error al actualizar el estado del usuario.";
            $mensaje_tipo = "error";
        }
        $stmt->close();
    }
}

// Si se envió búsqueda por número de identidad
if (isset($_GET['numero_identidad']) || isset($_POST['buscar_usuario'])) {
    $numero_identidad = $_GET['numero_identidad'] ?? $_POST['numero_identidad'] ?? '';
    if (!empty($numero_identidad)) {
        $stmt = $conexion->prepare(
            "SELECT nombre_completo, tipo_documento, numero_identidad, residencia, tipo_sangre, correo, telefono, estado_habilitacion 
             FROM usuarios WHERE numero_identidad = ?"
        );
        $stmt->bind_param("s", $numero_identidad);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            $usuario_encontrado = true;
        } else {
            $mensaje = "No se encontró ningún usuario con ese número de identidad.";
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
    <meta charset="UTF-8">
    <title>Habilitar/Inhabilitar Usuario</title>
    <link rel="stylesheet" href="../../../css/SU_admin/menu_SU_admin/habilitar_in.css">
</head>
<body>
<h1>DATASENA</h1>
<img src="../../../img/logo-sena.png" alt="Logo SENA" class="img">

<div class="forma-container">
    <h3>Habilitar / Inhabilitar Usuario</h3>

    <!-- Barra de búsqueda -->
    <div class="search-container">
        <h4>🔍 Buscar Usuario</h4>
        <form class="search-form" action="" method="get">
            <div class="search-group">
                <label for="buscar_identidad">Número de Identidad:</label>
                <input type="text" id="buscar_identidad" name="numero_identidad" required>
            </div>
            <button type="submit" class="search-btn">Buscar Usuario</button>
        </form>
    </div>

    <!-- Mensajes -->
    <?php if (!empty($mensaje)): ?>
        <div class="mensaje <?= $mensaje_tipo ?? 'error' ?>"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <?php if ($usuario_encontrado): ?>
        <!-- Formulario del usuario -->
        <form action="" method="post">
            <input type="hidden" name="numero_identidad" value="<?= htmlspecialchars($usuario['numero_identidad']) ?>">

<div class="forma-grid">
    <div>
        <label>Nombre completo:</label>
        <input type="text" value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" readonly class="md-input" />
    </div>
    <div>
        <label>Tipo de documento:</label>
        <input type="text" value="<?= htmlspecialchars($usuario['tipo_documento']) ?>" readonly class="md-input" />
    </div>
    <div>
        <label>Número de identidad:</label>
        <input type="text" value="<?= htmlspecialchars($usuario['numero_identidad']) ?>" readonly class="md-input" />
    </div>
    <div>
        <label>Residencia:</label>
        <input type="text" value="<?= htmlspecialchars($usuario['residencia']) ?>" readonly class="md-input" />
    </div>
    <div>
        <label>Correo:</label>
        <input type="email" value="<?= htmlspecialchars($usuario['correo']) ?>" readonly class="md-input" />
    </div>
    <div>
        <label>Teléfono:</label>
        <input type="text" value="<?= htmlspecialchars($usuario['telefono']) ?>" readonly class="md-input" />
    </div>
    <div>
        <label>Tipo de sangre:</label>
        <input type="text" value="<?= htmlspecialchars($usuario['tipo_sangre']) ?>" readonly class="md-input" />
    </div>
    <div>
        <label>Estado actual:</label>
        <input 
            type="text" 
            value="<?php
                if (isset($usuario['estado_habilitacion'])) {
                    echo $usuario['estado_habilitacion'] === 'Activo' ? 'Habilitado' : 'Inhabilitado';
                } else {
                    echo 'Desconocido';
                }
            ?>" 
            readonly 
            class="md-input" 
        />
    </div>
    <div>
        <label for="nuevo_estado">Cambiar estado a:</label>
        <select name="nuevo_estado" id="nuevo_estado" required class="md-input">
            <option value="">-- Seleccione nuevo estado --</option>
            <option value="Activo" <?= (isset($usuario['estado_cuenta']) && $usuario['estado_cuenta']==='Inactivo') ? 'selected' : '' ?>>✅ Habilitar Usuario</option>
            <option value="Inactivo" <?= (isset($usuario['estado_cuenta']) && $usuario['estado_cuenta']==='Activo') ? 'selected' : '' ?>>❌ Inhabilitar Usuario</option>
        </select>
    </div>
</div>


            <div class="buttons-container">
                <button type="submit" name="actualizar_estado" class="back">Actualizar Estado</button>
                <button type="button" class="habilitar-btn" onclick="window.location.href='../super_menu.html'">Regresar al Menú</button>
            </div>
        </form>
    <?php elseif (isset($_GET['numero_identidad']) && !$usuario_encontrado): ?>
        <div class="user-not-found">
            <h4>❌ Usuario no encontrado</h4>
            <p>Verifique el número e intente nuevamente.</p>
        </div>
    <?php else: ?>
        <div class="user-not-found">
            <h4>👆 Ingrese un número de identidad para buscar</h4>
        </div>
    <?php endif; ?>
</div>

<footer>
    &copy; Todos los derechos reservados al SENA
</footer>
</body>
</html>
