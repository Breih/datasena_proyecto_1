<?php
// Configuración de la conexión
$host = "localhost";
$user = "root";
$password = "";
$dbname = "datasenn_db";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y limpiar datos
    $tipo_documento = $conn->real_escape_string($_POST['tipo_documento'] ?? '');
    $numero_documento = $conn->real_escape_string($_POST['numero_documento'] ?? '');
    $nombres = $conn->real_escape_string($_POST['nombres'] ?? '');
    $apellidos = $conn->real_escape_string($_POST['apellidos'] ?? '');
    $nickname = $conn->real_escape_string($_POST['nickname'] ?? '');
    $correo_electronico = $conn->real_escape_string($_POST['correo_electronico'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar_contrasena = $_POST['confirmar_contrasena'] ?? '';
    $rol_id = isset($_POST['rol_id']) ? (int)$_POST['rol_id'] : 1;

    // Validar campos obligatorios
    if (
        empty($tipo_documento) || empty($numero_documento) || empty($nombres) || empty($apellidos) ||
        empty($nickname) || empty($correo_electronico) || empty($contrasena) || empty($confirmar_contrasena)
    ) {
        die("Por favor complete todos los campos. <a href='javascript:history.back()'>Volver</a>");
    }

    // Validar contraseñas iguales
    if ($contrasena !== $confirmar_contrasena) {
        die("Las contraseñas no coinciden. <a href='javascript:history.back()'>Volver</a>");
    }

    // Verificar duplicados
    $sql_check = "SELECT id FROM admin WHERE numero_documento=? OR nickname=? OR correo_electronico=?";
    $stmt_check = $conn->prepare($sql_check);
    if (!$stmt_check) {
        die("Error en consulta de verificación: " . $conn->error);
    }
    $stmt_check->bind_param("sss", $numero_documento, $nickname, $correo_electronico);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $stmt_check->close();
        die("Ya existe un administrador con el mismo número de documento, nickname o correo electrónico. <a href='javascript:history.back()'>Volver</a>");
    }
    $stmt_check->close();

    // Hashear contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar nuevo admin
    $sql_insert = "INSERT INTO admin (tipo_documento, numero_documento, nombres, apellidos, nickname, correo_electronico, contrasena, rol_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    if (!$stmt_insert) {
        die("Error en consulta de inserción: " . $conn->error);
    }
    $stmt_insert->bind_param("sssssssi", $tipo_documento, $numero_documento, $nombres, $apellidos, $nickname, $correo_electronico, $contrasena_hash, $rol_id);

    if ($stmt_insert->execute()) {
        echo "Administrador creado correctamente. <a href='../super_menu.html'>Volver al menú</a>";
    } else {
        echo "Error al crear el administrador: " . htmlspecialchars($stmt_insert->error) . " <a href='javascript:history.back()'>Volver</a>";
    }
    $stmt_insert->close();
} else {
    die("Acceso no permitido.");
}

$conn->close();
?>
