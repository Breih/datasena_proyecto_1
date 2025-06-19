<?php
// Conexión
$conexion = new mysqli("localhost", "root", "", "datasenn_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Inicializar datos de empresa vacíos
$empresa = [
    'id' => '',
    'tipo_documento' => '',
    'nit' => '',
    'nickname' => '',
    'telefono' => '',
    'correo' => '',
    'direccion' => '',
    'rol' => '',
    'actividad_economica' => ''
];

$mensaje = "";

// Si se envió el formulario para actualizar (POST con id)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    $tipo_documento = $_POST['tipo_documento'];
    $nit = $_POST['nit'];
    $nickname = $_POST['nickname'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];
    $rol = $_POST['rol'];
    $actividad_economica = $_POST['actividad_economica'];

    $stmt = $conexion->prepare("UPDATE empresas SET tipo_documento=?, nit=?, nickname=?, telefono=?, correo=?, direccion=?, rol=?, actividad_economica=? WHERE id=?");
    $stmt->bind_param("sissssssi", $tipo_documento, $nit, $nickname, $telefono, $correo, $direccion, $rol, $actividad_economica, $id);
    if ($stmt->execute()) {
        $mensaje = "Empresa actualizada correctamente.";
    } else {
        $mensaje = "Error al actualizar la empresa.";
    }
    $stmt->close();
}

// Si se envió el formulario para buscar (POST sin id pero con nit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['nickname']) && empty($_POST['id']))) {
    $nit = $_POST['nickname'];

    $stmt = $conexion->prepare("SELECT * FROM empresas WHERE nickname = ?");
    $stmt->bind_param("i", $nickname);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $empresa = $resultado->fetch_assoc();
    } else {
        $mensaje = "No se encontró empresa con ese NIT.";
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
    <title>Visualizar / Actualizar Empresa</title>
    <link rel="stylesheet" href="../../../css/SU_admin/menu_SU_admin/visualizar_usuario.css">
</head>
<body>
<header>DATASENA</header>
<img src="../img/logo-sena.png" alt="Logo SENA" class="img">

<div class="form-container">
    <h2>Visualizar / Actualizar Empresa</h2>

    <?php if ($mensaje): ?>
        <p style="color:green; font-weight:bold;"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <!-- Buscar empresa por NIT -->
    <form action="actualizar_empresa_su.php" method="post">
        <label for="buscar_nit">Buscar por NIT:</label>
        <input type="text" id="buscar_nit" name="nit" placeholder="Ingrese el NIT de la empresa" required>
        <button class="logout-btn" type="submit">Buscar</button>
    </form>

    <hr>

    <?php if (!empty($empresa['id'])): ?>
        <!-- Formulario de edición solo si hay empresa cargada -->
        <form class="form-grid" action="actualizar_empresa_su.php" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($empresa['id']) ?>">

            <div class="form-row">
                <label>Tipo de documento:</label>
                <input type="text" name="tipo_documento" value="<?= htmlspecialchars($empresa['tipo_documento']) ?>" required>
            </div>

            <div class="form-row">
                <label>NIT:</label>
                <input type="text" name="nit" value="<?= htmlspecialchars($empresa['nit']) ?>" required>
            </div>

            <div class="form-row">
                <label>Nickname:</label>
                <input type="text" name="nickname" value="<?= htmlspecialchars($empresa['nickname']) ?>" required>
            </div>

            <div class="form-row">
                <label>Teléfono:</label>
                <input type="text" name="telefono" value="<?= htmlspecialchars($empresa['telefono']) ?>" required>
            </div>

            <div class="form-row">
                <label>Correo:</label>
                <input type="email" name="correo" value="<?= htmlspecialchars($empresa['correo']) ?>" required>
            </div>

            <div class="form-row">
                <label>Dirección:</label>
                <input type="text" name="direccion" value="<?= htmlspecialchars($empresa['direccion']) ?>" required>
            </div>

            <div class="form-row">
                <label>Rol:</label>
                <input type="text" name="rol" value="<?= htmlspecialchars($empresa['rol']) ?>" required>
            </div>

            <div class="form-row">
                <label>Actividad Económica:</label>
                <input type="text" name="actividad_economica" value="<?= htmlspecialchars($empresa['actividad_economica']) ?>" required>
            </div>

            <div class="form-row">
                <button class="logout-btn" type="submit">Actualizar</button>
            </div>
        </form>
    <?php endif; ?>

    <div class="back_visual">
        <button class="logout-btn" onclick="window.location.href='super.menu.html'">Regresar</button>
    </div>
</div>

<footer>
    <a>&copy; Todos los derechos reservados al SENA</a>
</footer>
</body>
</html>
