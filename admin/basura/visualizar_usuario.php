<?php
// Conexión
$conexion = new mysqli("localhost", "root", "", "datasenn_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener número de identidad por GET
$numero_identidad = $_GET['numero_identidad'] ?? '';

// Preparar datos por defecto (si no se encuentra el usuario)
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

// Si se envió un número de identidad, buscar usuario
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Usuario</title>
    <link rel="icon" href="../../img/Logotipo_Datasena.png" type="image/x-icon">
    <link rel="stylesheet" href="visualizar_usuario.css">
</head>
<body>
    <header>DATASENA</header>
    <img src="../../img/logo-sena.png" alt="Logo SENA" class="img">

    <div class="form-container">
        <h2>Visualizar Usuario</h2>

        <form action="visualizar_usuario.php" method="get">
            <label for="buscar_id">Buscar por número de identidad:</label>
            <input type="text" id="buscar_id" name="numero_identidad" placeholder="Ingrese el número de identidad" required>
            <button type="submit">Buscar</button>
        </form>

        <hr>

<form class="form-grid">
    <div class="form-row">
        <label>Nombre completo:</label>
        <input type="text" value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" readonly>
    </div>

    <div class="form-row">
        <label>Tipo de documento:</label>
        <input type="text" value="<?= htmlspecialchars($usuario['tipo_documento']) ?>" readonly>
    </div>

    <div class="form-row">
        <label>Número de identidad:</label>
        <input type="text" value="<?= htmlspecialchars($usuario['numero_identidad']) ?>" readonly>
    </div>

    <div class="form-row">
        <label>Residencia:</label>
        <input type="text" value="<?= htmlspecialchars($usuario['residencia']) ?>" readonly>
    </div>

    <div class="form-row">
        <label>Correo:</label>
        <input type="text" value="<?= htmlspecialchars($usuario['correo']) ?>" readonly>
    </div>

    <div class="form-row">
        <label>Teléfono:</label>
        <input type="text" value="<?= htmlspecialchars($usuario['telefono']) ?>" readonly>
    </div>

    <div class="form-row">
        <label>Tipo de sangre:</label>
        <input type="text" value="<?= htmlspecialchars($usuario['tipo_sangre']) ?>" readonly>
    </div>

    <div class="form-row">
        <label>Estado:</label>
        <input type="text" value="<?= htmlspecialchars($usuario['estado']) ?>" readonly>
    </div>
</form>

        <div class="back_visual">
            <button class="logout-btn" onclick="window.location.href='super_menu.html'">Regresar</button>
        </div>
    </div>

    <footer>
        <a>&copy; Todos los derechos reservados al SENA</a>
    </footer>
</body>
</html>
