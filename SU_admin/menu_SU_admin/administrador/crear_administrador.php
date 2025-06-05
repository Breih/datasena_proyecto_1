    <?php
    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "datasenn_db");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recolectar los datos del formulario
$tipo_documento = $_POST['tipo_documento'] ?? '';
$nombre_completo = $_POST['nombre_completo'] ?? '';
$numero_documento = $_POST['numero_documento'] ?? '';
$correo = $_POST['correo'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$confirmar_contrasena = $_POST['confirmar_contrasena'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$tipo_sangre = $_POST['tipo_sangre'] ?? '';
$estado = $_POST['estado'] ?? '';

// Validación básica
if (empty($tipo_documento) || empty($nombre_completo) || empty($numero_documento) || empty($correo) || empty($contrasena) || empty($confirmar_contrasena) || empty($telefono) || empty($tipo_sangre) || empty($estado)) {
    echo "<script>
        alert('❌ Todos los campos son obligatorios.');
        window.history.back();
    </script>";
    exit;
}

// Verificar si las contraseñas coinciden
if ($contrasena !== $confirmar_contrasena) {
    echo "<script>
        alert('❌ Las contraseñas no coinciden.');
        window.history.back();
    </script>";
    exit;
}

// Insertar nuevo administrador
$sql = "INSERT INTO administradores (tipo_documento, nombre_completo, numero_documento, correo, contrasena, telefono, tipo_sangre, estado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssssss", $tipo_documento, $nombre_completo, $numero_documento, $correo, password_hash($contrasena, PASSWORD_DEFAULT), $telefono, $tipo_sangre, $estado);

// Ejecutar la consulta
if ($stmt->execute()) { 
    echo "<script>
        alert('✅ Administrador creado con éxito.');
        window.location.href = '/SU_admin/menu_SU_admin/super_menu.html';
    </script>";
} else {
    echo "<script>
        alert('❌ Error al crear el administrador: " . addslashes($stmt->error) . "');
        window.history.back();
    </script>";
}

// Cerrar la conexión y la sentencia
$stmt->close();
$conexion->close();
?>
