<?php
// Conexión a la base de datos (ajusta estos datos según tu configuración)
$host = "localhost";
$user = "root";
$password = ""; // Cambia a tu contraseña
$dbname = "datasenn_db";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario y limpiar para seguridad básica
$tipo_documento = $conn->real_escape_string($_POST['tipo_documento']);
$numero_documento = $conn->real_escape_string($_POST['numero_documento']);
$nombres = $conn->real_escape_string($_POST['nombres']);
$apellidos = $conn->real_escape_string($_POST['apellidos']);
$nickname = $conn->real_escape_string($_POST['nickname']);
$correo_electronico = $conn->real_escape_string($_POST['correo_electronico']);
$contrasena = $_POST['contrasena'];
$confirmar_contrasena = $_POST['confirmar_contrasena'];
$rol_id = isset($_POST['rol_id']) ? (int)$_POST['rol_id'] : NULL;

// Validación simple de contraseña
if ($contrasena !== $confirmar_contrasena) {
    die("Las contraseñas no coinciden. <a href='javascript:history.back()'>Volver</a>");
}

// Hashear la contraseña para seguridad
$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

// Validar que no existan número de documento, nickname o correo repetidos
$sql_check = "SELECT * FROM admin WHERE numero_documento='$numero_documento' OR nickname='$nickname' OR correo_electronico='$correo_electronico'";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    die("Ya existe un administrador con el mismo número de documento, nickname o correo electrónico. <a href='javascript:history.back()'>Volver</a>");
}

// Insertar en la tabla admin (ajusta el nombre de la tabla si es distinto)
$sql_insert = "INSERT INTO admin (tipo_documento, numero_documento, nombres, apellidos, nickname, correo_electronico, contrasena, rol_id)
VALUES ('$tipo_documento', '$numero_documento', '$nombres', '$apellidos', '$nickname', '$correo_electronico', '$contrasena_hash', " . ($rol_id ? $rol_id : "NULL") . ")";

if ($conn->query($sql_insert) === TRUE) {
    echo "Administrador creado correctamente. <a href='super.menu.html'>Volver al menú</a>";
} else {
    echo "Error: " . $conn->error . " <a href='javascript:history.back()'>Volver</a>";
}

$conn->close();
?>
