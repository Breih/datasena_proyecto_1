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
    'estado' => '',
    'contrasena' => '',
    'validacion' => ''
];

$mensaje = "";
$usuario_encontrado = false;

// Si se envió el formulario para actualizar estado (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_estado'])) {
    $numero_identidad = $_POST['numero_identidad'];
    $nuevo_estado = $_POST['nuevo_estado'];

    if (!empty($numero_identidad) && !empty($nuevo_estado)) {
        $stmt = $conexion->prepare("UPDATE usuarios SET estado = ? WHERE numero_identidad = ?");
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
        $stmt = $conexion->prepare("SELECT nombre_completo, tipo_documento, numero_identidad, residencia, tipo_sangre, correo, telefono, estado FROM usuarios WHERE numero_identidad = ?");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilitar Inhabilitar Usuario</title>
    <link rel="icon" href="../../img/Logotipo_Datasena.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/SU_admin/menu_SU_admin/habilitar_inhabilitar_SU.css">


</head>
<body>
    <h1>DATASENA</h1>
    <img src="../../img/logo-sena.png" alt="Logo SENA" class="img">

    <div class="forma-container">
        <h3>Habilitar / Inhabilitar Usuario</h3>
        
        <!-- Barra de búsqueda mejorada -->
        <div class="search-container">
            <h4>🔍 Buscar Usuario</h4>
            <form class="search-form" action="" method="get">
                <div class="search-group">
                    <label for="buscar_identidad">Número de Identidad:</label>
                    <input type="text" 
                           id="buscar_identidad" 
                           name="numero_identidad" 
                           placeholder="Ingrese el número de identidad" 
                           value="<?= htmlspecialchars($_GET['numero_identidad'] ?? '') ?>" 
                           required>
                </div>
                <button type="submit" class="search-btn">Buscar Usuario</button>
            </form>
        </div>

        <!-- Mensajes de estado -->
        <?php if (isset($mensaje) && !empty($mensaje)): ?>
            <div class="mensaje <?= $mensaje_tipo ?? 'error' ?>">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <?php if ($usuario_encontrado): ?>
            <!-- Formulario de datos del usuario encontrado -->
            <form action="" method="post">
                <input type="hidden" name="numero_identidad" value="<?= htmlspecialchars($usuario['numero_identidad']) ?>">
                
                <div class="forma-grid">
                    <!-- Primera columna -->
                    <div>
                        <div class="forma-row">
                            <label for="tipo_documento">Tipo de Documento:</label>
                            <input type="text" 
                                   id="tipo_documento" 
                                   name="tipo_documento" 
                                   value="<?= htmlspecialchars($usuario['tipo_documento']) ?>" 
                                   readonly 
                                   class="md-input">
                        </div>

                        <div class="forma-row">
                            <label for="nombre_completo">Nombre completo:</label>
                            <input type="text" 
                                   id="nombre_completo" 
                                   name="nombre_completo" 
                                   value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" 
                                   readonly 
                                   class="md-input">
                        </div>

                        <div class="forma-row">
                            <label for="tarjeta_identidad">Número de identidad:</label>
                            <input type="text" 
                                   id="tarjeta_identidad" 
                                   name="tarjeta_identidad" 
                                   value="<?= htmlspecialchars($usuario['numero_identidad']) ?>" 
                                   readonly 
                                   class="md-input">
                        </div>

                        <div class="forma-row">
                            <label for="residencia">Residencia:</label>
                            <input type="text" 
                                   id="residencia" 
                                   name="residencia" 
                                   value="<?= htmlspecialchars($usuario['residencia']) ?>" 
                                   readonly 
                                   class="md-input">
                        </div>
                        
                        <div class="forma-row">
                            <label for="correo">Correo:</label>
                            <input type="email" 
                                   id="correo" 
                                   name="correo" 
                                   value="<?= htmlspecialchars($usuario['correo']) ?>" 
                                   readonly 
                                   class="md-input">
                        </div>
                    </div>

                    <!-- Segunda columna -->
                    <div>
                        <div class="forma-row">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" 
                                   id="telefono" 
                                   name="telefono" 
                                   value="<?= htmlspecialchars($usuario['telefono'] ?? 'No registrado') ?>" 
                                   readonly 
                                   class="md-input">
                        </div>

                        <div class="forma-row">
                            <label for="contrasena">Contraseña:</label>
                            <input type="password" 
                                   id="contrasena" 
                                   name="contrasena" 
                                   value="********" 
                                   readonly 
                                   class="md-input">
                        </div>

                        <div class="forma-row">
                            <label for="tipo_de_sangre">Tipo de sangre:</label>
                            <input type="text" 
                                   id="tipo_de_sangre" 
                                   name="tipo_de_sangre" 
                                   value="<?= htmlspecialchars($usuario['tipo_sangre'] ?? 'No registrado') ?>" 
                                   readonly 
                                   class="md-input">
                        </div>

                        <div class="forma-row">
                            <label for="estado_actual">Estado actual:</label>
                            <input type="text" 
                                   id="estado_actual" 
                                   name="estado_actual" 
                                   value="<?= htmlspecialchars($usuario['estado']) ?>" 
                                   readonly 
                                   class="md-input">
                        </div>

                        <div class="forma-row">
                            <label for="nuevo_estado">Cambiar estado a:</label>
                            <select name="nuevo_estado" id="nuevo_estado" required class="md-input">
                                <option value="">-- Seleccione nuevo estado --</option>
                                <option value="Activo" <?= ($usuario['estado'] === 'Inactivo' || $usuario['estado'] === 'Inhabilitado') ? 'selected' : '' ?>>
                                    ✅ Habilitar Usuario
                                </option>
                                <option value="Inactivo" <?= ($usuario['estado'] === 'Activo' || $usuario['estado'] === 'Habilitado') ? 'selected' : '' ?>>
                                    ❌ Inhabilitar Usuario
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="buttons-container">
                    <button type="submit" name="actualizar_estado" class="back">
                         Actualizar Estado
                    </button>
                    <button type="button" class="habilitar-btn" onclick="window.location.href='super.menu.html'">
                         Regresar al Menú
                    </button>
                </div>
            </form>

        <?php elseif (isset($_GET['numero_identidad']) && !empty($_GET['numero_identidad']) && !$usuario_encontrado): ?>
            <!-- Mensaje cuando no se encuentra usuario -->
            <div class="user-not-found">
                <h4>❌ Usuario no encontrado</h4>
                <p>No se encontró ningún usuario con el número de identidad: 
                   <strong><?= htmlspecialchars($_GET['numero_identidad']) ?></strong>
                </p>
                <p>Por favor, verifique el número e intente nuevamente.</p>
            </div>
            
            <div class="buttons-container">
                <button type="button" class="habilitar-btn" onclick="window.location.href='super.menu.html'">
                    Regresar al Menú
                </button>
            </div>

        <?php else: ?>
            <!-- Estado inicial - sin búsqueda -->
            <div class="user-not-found">
                <h4>👆 Ingrese un número de identidad para buscar</h4>
                <p>Use la barra de búsqueda de arriba para encontrar el usuario que desea habilitar o inhabilitar.</p>
            </div>
            
            <div class="buttons-container">
                <button type="button" class="habilitar-btn" onclick="window.location.href='super.menu.html'">
                 Regresar al Menú
                </button>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <a>&copy; Todos los derechos reservados al SENA</a>
    </footer>
</body>
</html>