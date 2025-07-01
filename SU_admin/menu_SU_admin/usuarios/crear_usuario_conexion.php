<?php
$errores = [];
$datos = [];
$fichas = [];

// Conexión a la base de datos para obtener fichas
try {
    $pdo = new PDO("mysql:host=localhost;dbname=datasenn_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT numero_ficha FROM programas");
    $fichas = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Error al conectar o consultar: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $campos = ['nombre_completo', 'tipo_documento', 'numero_identidad', 'residencia', 'tipo_sangre', 'correo', 'telefono', 'contrasena', 'validacion', 'activacion', 'numero_ficha'];
    foreach ($campos as $campo) {
        $datos[$campo] = trim($_POST[$campo] ?? '');
    }

    if (in_array('', $datos)) {
        $errores['general'] = "Todos los campos son obligatorios.";
    }

    if ($datos['contrasena'] !== $datos['validacion']) {
        $errores['contrasena'] = "❌ Las contraseñas no coinciden.";
    }

    if (empty($errores)) {
        $conexion = new mysqli("localhost", "root", "", "datasenn_db");
        if ($conexion->connect_error) die("Conexión fallida: " . $conexion->connect_error);

        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE numero_identidad = ?");
        $stmt->bind_param("s", $datos['numero_identidad']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errores['numero_identidad'] = "❌ Ya está registrado.";
        }
        $stmt->close();

        if (empty($errores)) {
            $hashed_pass = password_hash($datos['contrasena'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nombre_completo, tipo_documento, numero_identidad, residencia, tipo_sangre, correo, telefono, contrasena, estado, numero_ficha)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param(
                "ssssssssss",
                $datos['nombre_completo'],
                $datos['tipo_documento'],
                $datos['numero_identidad'],
                $datos['residencia'],
                $datos['tipo_sangre'],
                $datos['correo'],
                $datos['telefono'],
                $hashed_pass,
                $datos['activacion'],
                $datos['numero_ficha']
            );

            if ($stmt->execute()) {
                echo "<script>alert('Usuario creado con éxito'); window.location.href='../super_menu.html';</script>";
                exit;
            } else {
                $errores['general'] = "Error al insertar: " . $stmt->error;
            }
            $stmt->close();
        }
        $conexion->close();
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear usuario Superadmin</title>
    <link rel="icon" href="../../../img/Logotipo_Datasena.png">
    <link rel="stylesheet" href="../../../css/SU_admin/menu_SU_admin/crear_usuario_SU.css">
    <style>
        .error { color: red; font-size: 0.9em; margin-top: 3px; }
        .mensaje-error { background: #ffe0e0; padding: 10px; margin-bottom: 10px; border-left: 4px solid #d00; }
    </style>
</head>
<body>
    <h2>DATASENA</h2>
    <img src="../../../img/logo-sena.png" alt="Logo SENA" class="img">

    <div class="form-container">
        <h1>Crear usuario</h1>
        <?php if (!empty($errores['general'])): ?>
            <div class="mensaje-error"><?= htmlspecialchars($errores['general']) ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-grid">
                <div>
                    <div class="form-row">
                        <label for="nombre_completo">Nombre completo:</label>
                        <input type="text" name="nombre_completo" required
                            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+" title="Solo letras y espacios"
                            value="<?= htmlspecialchars($datos['nombre_completo'] ?? '') ?>">
                    </div>
                    <div class="form-row">
                        <label for="tipo_documento">Tipo de documento:</label>
                        <select name="tipo_documento" required>
                            <option value="">Seleccione</option>
                            <option value="cc" <?= ($datos['tipo_documento'] ?? '') === 'cc' ? 'selected' : '' ?>>Cédula</option>
                            <option value="ce" <?= ($datos['tipo_documento'] ?? '') === 'ce' ? 'selected' : '' ?>>Extranjería</option>
                            <option value="ti" <?= ($datos['tipo_documento'] ?? '') === 'ti' ? 'selected' : '' ?>>Tarjeta</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="numero_identidad">Número de identidad:</label>
                        <input type="text" name="numero_identidad" required pattern="\d{10}"
                            value="<?= htmlspecialchars($datos['numero_identidad'] ?? '') ?>">
                        <div class="error"><?= $errores['numero_identidad'] ?? '' ?></div>
                    </div>
                    <div class="form-row">
                        <label for="residencia">Residencia:</label>
                        <input type="text" name="residencia" required value="<?= htmlspecialchars($datos['residencia'] ?? '') ?>">
                    </div>
                    <div class="form-row">
                        <label for="tipo_sangre">Tipo de sangre:</label>
                        <select name="tipo_sangre" required>
                            <option value="">Seleccione</option>
                            <?php foreach (["A+","A-","B+","B-","AB+","AB-","O+","O-"] as $tipo): ?>
                                <option value="<?= $tipo ?>" <?= ($datos['tipo_sangre'] ?? '') === $tipo ? 'selected' : '' ?>><?= $tipo ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <div class="form-row">
                        <label for="correo">Correo electrónico:</label>
                        <input type="email" name="correo" required value="<?= htmlspecialchars($datos['correo'] ?? '') ?>">
                    </div>
                    <div class="form-row">
                        <label for="telefono">Teléfono:</label>
                        <input type="tel" name="telefono" required pattern="\d{10}" value="<?= htmlspecialchars($datos['telefono'] ?? '') ?>">
                    </div>
                    <div class="form-row">
                        <label for="contrasena">Contraseña:</label>
                        <input type="password" name="contrasena" required
                            pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}">
                    </div>
                    <div class="form-row">
                        <label for="validacion">Confirmar contraseña:</label>
                        <input type="password" name="validacion" required>
                        <div class="error"><?= $errores['contrasena'] ?? '' ?></div>
                    </div>
                    <div class="form-row">
                        <label for="activacion">Estado:</label>
                        <select name="activacion" required>
                            <option value="">Seleccione</option>
                            <option value="activo" <?= ($datos['activacion'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="inactivo" <?= ($datos['activacion'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label for="numero_ficha">Número de ficha:</label>
                        <select name="numero_ficha" required>
                            <option value="">Seleccione ficha</option>
                            <?php foreach ($fichas as $ficha): ?>
                                <option value="<?= $ficha ?>" <?= ($datos['numero_ficha'] ?? '') === $ficha ? 'selected' : '' ?>>
                                    <?= $ficha ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="buttons-container">
                <button type="submit" class="logout-btn">Crear</button>
                <button type="button" class="logout-btn" onclick="window.location.href='../super_menu.html'">Regresar</button>
            </div>
        </form>
    </div>

    <footer>
        <a>&copy; Todos los derechos reservados al SENA</a>
    </footer>
</body>
</html>
