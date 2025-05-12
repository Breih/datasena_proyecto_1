<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "datasenn_db");

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
$validacion = $_POST['validacion'] ?? ''; // Confirmar la contraseña
$estado = $_POST['activacion'] ?? '';

// Validación básica
if (empty($nombre_completo) || empty($tipo_documento) || empty($numero_identidad) || empty($residencia) || empty($tipo_sangre) || empty($correo) || empty($telefono) || empty($contrasena) || empty($validacion) || empty($estado)) {
    echo "<script>
        alert('❌ Todos los campos son obligatorios.');
        window.history.back();
    </script>";
    exit;
}

// Verificar si las contraseñas coinciden
if ($contrasena !== $validacion) {
    echo "<script>
        alert('❌ Las contraseñas no coinciden.');
        window.history.back();
    </script>";
    exit;
}

// Insertar los datos en la base de datos
$sql = "INSERT INTO usuarios (nombre_completo, tipo_documento, numero_identidad, residencia, tipo_sangre, correo, telefono, contrasena, estado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssssss", $nombre_completo, $tipo_documento, $numero_identidad, $residencia, $tipo_sangre, $correo, $telefono, password_hash($contrasena, PASSWORD_DEFAULT), $estado);

// Ejecutar la consulta
if ($stmt->execute()) { 
    echo "<script>
        alert('✅ Usuario creado con éxito.');
        window.location.href = 'super_menu.html'; // Redirigir al menú
    </script>";
} else {
    echo "<script>
        alert('❌ Error al crear el usuario: " . addslashes($stmt->error) . "');
        window.history.back();
    </script>";
}

// Cerrar la conexión y la sentencia
$stmt->close();
$conexion->close();
?>
