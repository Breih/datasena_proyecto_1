<?php
$conexion = new mysqli("localhost", "root", "123456", "datasenn_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recolectar los datos del formulario
$nombre_completo = $_POST['nombre_completo'] ?? '';
$tipo_documento = $_POST['tipo_documento'] ?? '';
$numero_identidad = $_POST['numero_identidad'] ?? '';
$residencia = $_POST['residencia'] ?? '';
$tipo_sangre = $_POST['tipo_sangre'] ?? '';
$correo = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$validacion = $_POST['validacion'] ?? '';
$estado = $_POST['activacion'] ?? '';

// Validaciones
if (empty($nombre_completo) || empty($tipo_documento) || empty($numero_identidad) || empty($residencia) || empty($tipo_sangre) || empty($correo) || empty($telefono) || empty($contrasena) || empty($validacion) || empty($estado)) {
    echo "<script>
        alert('❌ Todos los campos son obligatorios.');
        window.history.back();
    </script>";
    exit;
}

if ($contrasena !== $validacion) {
    echo "<script>
        alert('❌ Las contraseñas no coinciden.');
        window.history.back();
    </script>";
    exit;
}

// Verificar si ya existe el numero de identidad
$verificar_sql = "SELECT id FROM usuarios WHERE numero_identidad = ?";
$verificar_stmt = $conexion->prepare($verificar_sql);
$verificar_stmt->bind_param("s", $numero_identidad);
$verificar_stmt->execute();
$verificar_stmt->store_result();

if ($verificar_stmt->num_rows > 0) {
    echo "<script>
        alert('❌ El número de identidad ya está registrado.');
        window.history.back();
    </script>";
    exit;
}
$verificar_stmt->close();

// Insertar el usuario
$sql = "INSERT INTO usuarios (nombre_completo, tipo_documento, numero_identidad, residencia, tipo_sangre, correo, telefono, contrasena, estado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$hashed_pass = password_hash($contrasena, PASSWORD_DEFAULT);
$stmt->bind_param("sssssssss", $nombre_completo, $tipo_documento, $numero_identidad, $residencia, $tipo_sangre, $correo, $telefono, $hashed_pass, $estado);

if ($stmt->execute()) {
    echo "<script>
        alert('✅ Usuario creado con éxito.');
        window.location.href = 'super.menu.html';
    </script>";
} else {
    echo "<script>
        alert('❌ Error al crear el usuario: " . addslashes($stmt->error) . "');
        window.history.back();
    </script>";
}

$stmt->close();
$conexion->close();
?>
