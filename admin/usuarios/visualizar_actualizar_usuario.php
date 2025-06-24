<?php
// Conexión
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
    'estado' => ''
];

$mensaje = "";

// Si se envió el formulario para actualizar (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar datos del formulario
    $nombre_completo = $_POST['nombre_completo'];
    $tipo_documento = $_POST['tipo_documento'];
    $numero_identidad = $_POST['numero_identidad'];
    $residencia = $_POST['residencia'];
    $tipo_sangre = $_POST['tipo_sangre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $estado = $_POST['estado'];

    // Recuperar el número de identidad original desde el GET o el formulario
    $numero_identidad_original = $_POST['numero_identidad_original'] ?? $_GET['numero_identidad'];

    // Validación: Nombre completo solo debe contener letras y espacios
    if (!preg_match("/^[a-zA-Z\s]+$/", $nombre_completo)) {
        $mensaje = "El nombre completo solo debe contener letras y espacios.";
    }
    // Validación: Número de identidad solo debe contener números
    elseif (!preg_match("/^[0-9]+$/", $numero_identidad)) {
        $mensaje = "El número de identidad solo debe contener números.";
    } else {
        // Si el número de identidad ha cambiado, verificar si ya está en uso
        if ($numero_identidad !== $numero_identidad_original) {
            $stmt = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE numero_identidad = ?");
            $stmt->bind_param("s", $numero_identidad);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $mensaje = "El número de identidad ya está en uso.";
            } else {
                // Actualizar usuario
                $stmt = $conexion->prepare("UPDATE usuarios SET nombre_completo=?, tipo_documento=?, numero_identidad=?, residencia=?, tipo_sangre=?, correo=?, telefono=?, estado=? WHERE numero_identidad=?");
                $stmt->bind_param("sssssssss", $nombre_completo, $tipo_documento, $numero_identidad, $residencia, $tipo_sangre, $correo, $telefono, $estado, $numero_identidad_original);
                if ($stmt->execute()) {
                    $mensaje = "Usuario actualizado correctamente.";
                } else {
                    $mensaje = "Error al actualizar el usuario.";
                }
                $stmt->close();
            }
        } else {
            // Si el número de identidad no cambió, solo actualizamos los demás campos
            $stmt = $conexion->prepare("UPDATE usuarios SET nombre_completo=?, tipo_documento=?, residencia=?, tipo_sangre=?, correo=?, telefono=?, estado=? WHERE numero_identidad=?");
            $stmt->bind_param("ssssssss", $nombre_completo, $tipo_documento, $residencia, $tipo_sangre, $correo, $telefono, $estado, $numero_identidad_original);

            if ($stmt->execute()) {
                $mensaje = "Usuario actualizado correctamente.";
            } else {
                $mensaje = "Error al actualizar el usuario.";
            }
            $stmt->close();
        }
    }
}

// Si se envió por GET (para buscar usuario)
$numero_identidad = $_GET['numero_identidad'] ?? ($_POST['numero_identidad'] ?? '');
if ($numero_identidad !== '') {
    $stmt = $conexion->prepare("SELECT nombre_completo, tipo_documento, numero_identidad, residencia, tipo_sangre, correo, telefono, estado FROM usuarios WHERE numero_identidad = ?");
    $stmt->bind_param("s", $numero_identidad);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
    }

    $stmt->close();
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <link rel="icon" href="../../img/Logotipo_Datasena.png" type="image/x-icon">
        <title>Visualizar y Actualizar Usuario</title>
        <link rel="stylesheet" href="css/visualizar_actualizar_usuarios.css">
    </head>
    <body>
        <!-- Línea azul superior con logo del gobierno -->
        <div class="blue-line-top">
            <img src="../../img/gov.png" alt="gov" class="gov-logo">
        </div>

        <header>DATASENA</header>
        <img src="../../img/logo-sena.png" alt="Logo SENA" class="logo-sena">

        <div class="form-container">
            <h2>Visualizar / Actualizar Usuario</h2>

            <!-- Mensaje de éxito o error -->
            <?php if ($mensaje): ?>
                <p style="color:green; font-weight:bold;"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <!-- Buscar por número de identidad -->
            <form action="visualizar_usuario.php" method="get">
                <label for="buscar_id">Buscar por número de identidad:</label>
                <input type="text" id="buscar_id" name="numero_identidad" placeholder="Ingrese el número de identidad" required>
                <button class="logout-btn" type="submit">Buscar</button>
            </form>

            <hr>
            <?php if (!empty($usuario['id'])): ?>
                <!-- Formulario de edición solo si hay usuario cargado -->
                <form class="form-grid" action="visualizar_usuario.php" method="post">
                    <div class="form-row">
                        <label>Nombre completo:</label>
                        <input type="text" name="nombre_completo" value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" required pattern="[A-Za-z\s]+" title="El nombre completo solo puede contener letras y espacios">
                    </div>

                    <div class="form-row">
                        <label>Tipo de documento:</label>
                        <select name="tipo_documento" required>
                            <option value="Cédula de Ciudadanía" <?= $usuario['tipo_documento'] == 'Cédula de Ciudadanía' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                            <option value="Tarjeta de Identidad" <?= $usuario['tipo_documento'] == 'Tarjeta de Identidad' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                            <option value="Pasaporte" <?= $usuario['tipo_documento'] == 'Pasaporte' ? 'selected' : '' ?>>Pasaporte</option>
                            <option value="Cédula de Extranjería" <?= $usuario['tipo_documento'] == 'Cédula de Extranjería' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label>Número de identidad:</label>
                        <input type="text" name="numero_identidad" value="<?= htmlspecialchars($usuario['numero_identidad']) ?>" required>
                        <input type="hidden" name="numero_identidad_original" value="<?= htmlspecialchars($usuario['numero_identidad']) ?>">
                    </div>
                    <div class="form-row">
                        <label>Residencia:</label>
                        <input type="text" name="residencia" value="<?= htmlspecialchars($usuario['residencia']) ?>" required>
                    </div>

                    <div class="form-row">
                        <label>Correo:</label>
                        <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
                    </div>

                    <div class="form-row">
                        <label>Teléfono:</label>
                        <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" required>
                    </div>

                    <div class="form-row">
                        <label>Tipo de sangre:</label>
                        <select name="tipo_sangre" required>
                            <option value="A+" <?= $usuario['tipo_sangre'] == 'A+' ? 'selected' : '' ?>>A+</option>
                            <option value="A-" <?= $usuario['tipo_sangre'] == 'A-' ? 'selected' : '' ?>>A-</option>
                            <option value="B+" <?= $usuario['tipo_sangre'] == 'B+' ? 'selected' : '' ?>>B+</option>
                            <option value="B-" <?= $usuario['tipo_sangre'] == 'B-' ? 'selected' : '' ?>>B-</option>
                            <option value="AB+" <?= $usuario['tipo_sangre'] == 'AB+' ? 'selected' : '' ?>>AB+</option>
                            <option value="AB-" <?= $usuario['tipo_sangre'] == 'AB-' ? 'selected' : '' ?>>AB-</option>
                            <option value="O+" <?= $usuario['tipo_sangre'] == 'O+' ? 'selected' : '' ?>>O+</option>
                            <option value="O-" <?= $usuario['tipo_sangre'] == 'O-' ? 'selected' : '' ?>>O-</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label>Estado:</label>
                        <select name="estado" required>
                            <option value="Activo" <?= $usuario['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="Inactivo" <?= $usuario['estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <button class="logout-btn" type="submit">Actualizar</button>
                    </div>
                </form>
            <?php endif; ?>
            <div class="back_visual">
                <button class="logout-btn" onclick="window.location.href='../admin.menu.html'">Regresar</button>
            </div>
        </div>

        <!-- Línea azul inferior con logo del gobierno -->
        <div class="blue-line-bottom">
            <img class="gov-logo"src="../../img/gov.png" alt="gov" >
        </div>

        <footer>
            <a>&copy; Todos los derechos reservados al SENA</a>
        </footer>
    </body>
</html>