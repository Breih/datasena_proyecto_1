<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conexion = new mysqli("localhost", "root", "", "datasenn_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$usuario = [
    'nombre_completo' => '', 'tipo_documento' => '', 'numero_identidad' => '',
    'residencia' => '', 'tipo_sangre' => '', 'correo' => '', 'telefono' => '', 'estado' => ''
];
$errores = [];
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($usuario as $campo => &$valor) {
        $valor = trim($_POST[$campo] ?? '');
    }
    unset($valor);

    $numero_identidad_original = $_POST['numero_identidad_original'] ?? $_GET['numero_identidad'] ?? '';

    if (!preg_match("/^[a-zA-Z\sÀ-ÿ]+$/", $usuario['nombre_completo'])) {
        $errores['nombre_completo'] = "El nombre completo solo debe contener letras y espacios.";
    }
    if (!preg_match("/^\d{10}$/", $usuario['numero_identidad'])) {
        $errores['numero_identidad'] = "El número de identidad debe contener exactamente 10 dígitos.";
    }
    if (!filter_var($usuario['correo'], FILTER_VALIDATE_EMAIL)) {
        $errores['correo'] = "El correo electrónico no es válido.";
    }
    if (!preg_match("/^\d{10}$/", $usuario['telefono'])) {
        $errores['telefono'] = "El teléfono debe contener exactamente 10 dígitos.";
    }
    if (!preg_match("/^[\p{L}\p{N}\s\-,.#]+$/u", $usuario['residencia'])) {
        $errores['residencia'] = "La residencia contiene caracteres inválidos.";
    }

    if (empty($errores)) {
        if ($usuario['numero_identidad'] !== $numero_identidad_original) {
            $stmt = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE numero_identidad = ?");
            $stmt->bind_param("s", $usuario['numero_identidad']);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $errores['numero_identidad'] = "El número de identidad ya está en uso.";
            }
        }
    }

    if (empty($errores)) {
        if ($usuario['numero_identidad'] !== $numero_identidad_original) {
            $stmt = $conexion->prepare("UPDATE usuarios SET nombre_completo=?, tipo_documento=?, numero_identidad=?, residencia=?, tipo_sangre=?, correo=?, telefono=?, estado=? WHERE numero_identidad=?");
            $stmt->bind_param("sssssssss",
                $usuario['nombre_completo'], $usuario['tipo_documento'], $usuario['numero_identidad'],
                $usuario['residencia'], $usuario['tipo_sangre'], $usuario['correo'], $usuario['telefono'],
                $usuario['estado'], $numero_identidad_original);
        } else {
            $stmt = $conexion->prepare("UPDATE usuarios SET nombre_completo=?, tipo_documento=?, residencia=?, tipo_sangre=?, correo=?, telefono=?, estado=? WHERE numero_identidad=?");
            $stmt->bind_param("ssssssss",
                $usuario['nombre_completo'], $usuario['tipo_documento'], $usuario['residencia'],
                $usuario['tipo_sangre'], $usuario['correo'], $usuario['telefono'], $usuario['estado'], $numero_identidad_original);
        }

        if ($stmt->execute()) {
            $mensaje = "Usuario actualizado correctamente.";
        } else {
            $mensaje = "Error al actualizar el usuario: " . $stmt->error;
        }
        $stmt->close();
    }
}

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
    <link rel="icon" href="../img/Logotipo_Datasena.png" type="image/x-icon">
    <title>Visualizar y Actualizar Usuario</title>
    <link rel="stylesheet" href="../../../css/SU_admin/menu_SU_admin/visualizar_usuario.css">
    <style>
        .error-msg {
            color: red;
            font-size: 0.85em;
            margin-top: 3px;
        }
        .form-row input:invalid {
            border-color: red;
        }
    </style>
</head>
<body>
<header>DATASENA</header>
<img src="../../img/logo-sena.png" alt="Logo SENA" class="img">

<div class="form-container">
    <h2>Visualizar / Actualizar Usuario</h2>

    <?php if ($mensaje): ?>
        <p style="color:green; font-weight:bold;">
            <?= htmlspecialchars($mensaje) ?>
        </p>
    <?php endif; ?>

    <form action="visualizar_usuario.php" method="get">
        <label for="buscar_id">Buscar por número de identidad:</label>
        <input type="text" id="buscar_id" name="numero_identidad" placeholder="Ingrese el número de identidad" required>
        <button class="logout-btn" type="submit">Buscar</button>
    </form>

    <hr>

    <form class="form-grid" action="visualizar_usuario.php" method="post">
        <div class="form-row">
            <label>Nombre completo:</label>
            <input type="text" name="nombre_completo" value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" required>
            <?php if (!empty($errores['nombre_completo'])): ?><div class="error-msg"><?= $errores['nombre_completo'] ?></div><?php endif; ?>
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
            <?php if (!empty($errores['numero_identidad'])): ?><div class="error-msg"><?= $errores['numero_identidad'] ?></div><?php endif; ?>
        </div>

        <div class="form-row">
            <label>Residencia:</label>
            <input type="text" name="residencia" value="<?= htmlspecialchars($usuario['residencia']) ?>" required>
            <?php if (!empty($errores['residencia'])): ?><div class="error-msg"><?= $errores['residencia'] ?></div><?php endif; ?>
        </div>

        <div class="form-row">
            <label>Correo:</label>
            <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
            <?php if (!empty($errores['correo'])): ?><div class="error-msg"><?= $errores['correo'] ?></div><?php endif; ?>
        </div>

        <div class="form-row">
            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" required>
            <?php if (!empty($errores['telefono'])): ?><div class="error-msg"><?= $errores['telefono'] ?></div><?php endif; ?>
        </div>

        <div class="form-row">
            <label>Tipo de sangre:</label>
            <select name="tipo_sangre" required>
                <?php foreach (["A+","A-","B+","B-","AB+","AB-","O+","O-"] as $tipo): ?>
                    <option value="<?= $tipo ?>" <?= $usuario['tipo_sangre'] == $tipo ? 'selected' : '' ?>><?= $tipo ?></option>
                <?php endforeach; ?>
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

    <div class="back_visual">
        <button class="logout-btn" onclick="window.location.href='../super_menu.html'">Regresar</button>
    </div>
</div>

<footer>
    <a>&copy; Todos los derechos reservados al SENA</a>
</footer>
</body>
</html>
