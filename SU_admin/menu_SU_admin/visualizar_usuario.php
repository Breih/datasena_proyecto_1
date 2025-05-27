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
    $nombre_completo = $_POST['nombre_completo'];
    $tipo_documento = $_POST['tipo_documento'];
    $numero_identidad = $_POST['numero_identidad'];
    $residencia = $_POST['residencia'];
    $tipo_sangre = $_POST['tipo_sangre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $estado = $_POST['estado'];

    $stmt = $conexion->prepare("UPDATE usuarios SET nombre_completo=?, tipo_documento=?, residencia=?, tipo_sangre=?, correo=?, telefono=?, estado=? WHERE numero_identidad=?");
    $stmt->bind_param("ssssssss", $nombre_completo, $tipo_documento, $residencia, $tipo_sangre, $correo, $telefono, $estado, $numero_identidad);
    if ($stmt->execute()) {
        $mensaje = "Usuario actualizado correctamente.";
    } else {
        $mensaje = "Error al actualizar el usuario.";
    }
    $stmt->close();
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

<!-- HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visualizar y Actualizar Usuario</title>
    <link rel="stylesheet" href="../../css/SU_admin/menu_SU_admin/visualizar_usuario.css">
</head>
<body>
<header>DATASENA</header>
<img src="../../img/logo-sena.png" alt="Logo SENA" class="img">

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

    <!-- Formulario de edición -->
<form class="form-grid" action="visualizar_usuario.php" method="post">
    <div class="form-row">
        <label>Nombre completo:</label>
        <input type="text" name="nombre_completo" value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" required>
    </div>

    <div class="form-row">
        <label>Tipo de documento:</label>
        <input type="text" name="tipo_documento" value="<?= htmlspecialchars($usuario['tipo_documento']) ?>" required>
    </div>

    <div class="form-row">
        <label>Número de identidad:</label>
        <input type="text" name="numero_identidad" value="<?= htmlspecialchars($usuario['numero_identidad']) ?>" readonly>
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
        <input type="text" name="tipo_sangre" value="<?= htmlspecialchars($usuario['tipo_sangre']) ?>" required>
    </div>

    <div class="form-row">
        <label>Estado:</label>
        <input type="text" name="estado" value="<?= htmlspecialchars($usuario['estado']) ?>" required>
    </div>

    <div class="form-row">
        <button class="logout-btn" type="submit">Actualizar</button>
    </div>
</form>

    <div class="back_visual">
        <button class="logout-btn" onclick="window.location.href='super.menu.html'">Regresar</button>
    </div>
</div>

<footer>
    <a>&copy; Todos los derechos reservados al SENA</a>
</footer>
</body>
</html>
