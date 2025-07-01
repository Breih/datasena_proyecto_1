<?php
// Conexión
$conexion = new mysqli("localhost", "root", "", "datasenn_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Datos por defecto
$empresas = [
    'id' => '',
    'tipo_documento' => '',
    'numero_identidad' => '',
    'nickname' => '',
    'telefono' => '',
    'correo' => '',
    'direccion' => '',
    'actividad_economica' => ''
];

$mensaje = "";

// Actualizar empresas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    $tipo_documento = $_POST['tipo_documento'];
    $numero_identidad = $_POST['numero_identidad'];
    $nickname = $_POST['nickname'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];
    $actividad_economica = $_POST['actividad_economica'];

    $stmt = $conexion->prepare("UPDATE empresas SET tipo_documento=?, numero_identidad=?, nickname=?, telefono=?, correo=?, direccion=?, actividad_economica=? WHERE id=?");
    $stmt->bind_param("sssssssi", $tipo_documento, $numero_identidad, $nickname, $telefono, $correo, $direccion, $actividad_economica, $id);

    if ($stmt->execute()) {
        $mensaje = "Empresa actualizada correctamente.";
    } else {
        $mensaje = "Error al actualizar la empresa.";
    }

    $stmt->close();
}

// Buscar empresas por número de identidad o nickname
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['id']) && isset($_POST['dato_busqueda'])) {
    $dato = $_POST['dato_busqueda'];

    $sql = "SELECT * FROM empresas WHERE numero_identidad = ? OR nickname = ?";
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt->bind_param("ss", $dato, $dato);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $empresas = $resultado->fetch_assoc();
    } else {
        $mensaje = "No se encontró empresa con ese número de identidad o nickname.";
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
    <title>Visualizar / Actualizar Empresas</title>
    <link rel="stylesheet" href="../../../css/SU_admin/menu_SU_admin/visualizar_usuario.css">
</head>
<body>
<header>DATASENA</header>
<img src="../img/logo-sena.png" alt="Logo SENA" class="img">

<div class="form-container">
    <h2>Visualizar / Actualizar Empresas</h2>

    <?php if ($mensaje): ?>
        <p style="color:green; font-weight:bold;"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <!-- Buscar empresas -->
    <form action="actualizar_empresa_su.php" method="post">
        <label for="buscar_dato">Buscar por número de identidad o nickname:</label>
        <input type="text" id="buscar_dato" name="dato_busqueda" placeholder="Ingrese número de identidad o nickname" required>
        <button class="logout-btn" type="submit">Buscar</button>
    </form>

    <hr>

    <?php if (!empty($empresas['id'])): ?>
        <!-- Formulario de edición -->
        <form class="form-grid" action="actualizar_empresa_su.php" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($empresas['id']) ?>">

            <div class="form-row">
                <label>Tipo de documento:</label>
                <input type="text" name="tipo_documento" value="<?= htmlspecialchars($empresas['tipo_documento']) ?>" required>
            </div>

            <div class="form-row">
                <label>Número de identidad:</label>
                <input type="text" name="numero_identidad" value="<?= htmlspecialchars($empresas['numero_identidad']) ?>" required>
            </div>

            <div class="form-row">
                <label>Nombre de la empresa:</label>
                <input type="text" name="nickname" value="<?= htmlspecialchars($empresas['nickname']) ?>" required>
            </div>

            <div class="form-row">
                <label>Teléfono:</label>
                <input type="text" name="telefono" value="<?= htmlspecialchars($empresas['telefono']) ?>" pattern="\d{10}" title="Debe tener exactamente 10 dígitos" required>
            </div>

            <div class="form-row">
                <label>Correo electrónico:</label>
                <input type="email" name="correo" value="<?= htmlspecialchars($empresas['correo']) ?>" required>
            </div>

            <div class="form-row">
                <label>Dirección:</label>
                <input type="text" name="direccion" value="<?= htmlspecialchars($empresas['direccion']) ?>" required>
            </div>

            <div class="form-row">
                <label>Actividad Económica:</label>
                <input type="text" name="actividad_economica" value="<?= htmlspecialchars($empresas['actividad_economica']) ?>" required>
            </div>

            <div class="form-row">
                <button class="logout-btn" type="submit">Actualizar</button>
            </div>
        </form>
    <?php endif; ?>

    <div class="back_visual">
        <button class="logout-btn" onclick="window.location.href='../super_menu.html'">Regresar</button>
    </div>
</div>

<footer>
    <a>&copy; Todos los derechos reservados al SENA</a>
</footer>
</body>
</html>
