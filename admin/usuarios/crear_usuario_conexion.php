<?php
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
$validacion = $_POST['validacion'] ?? '';
$estado = $_POST['activacion'] ?? '';

// Validaciones: Asegurarse de que no estén vacíos
if (empty($nombre_completo) || empty($tipo_documento) || empty($numero_identidad) || empty($residencia) || empty($tipo_sangre) || empty($correo) || empty($telefono) || empty($contrasena) || empty($validacion) || empty($estado)) {
    echo "<script>
        alert('❌ Todos los campos son obligatorios.');
        window.history.back();
    </script>";
    exit;
}

// Validación del nombre completo (solo letras y espacios)
if (!preg_match('/^[a-zA-Z\s]+$/', $nombre_completo)) {
    echo "<script>
        alert('❌ El nombre solo debe contener letras y espacios.');
        window.history.back();
    </script>";
    exit;
}

// Validación del número de identidad (solo números)
if (!ctype_digit($numero_identidad)) {
    echo "<script>
        alert('❌ El número de identidad solo debe contener números.');
        window.history.back();
    </script>";
    exit;
}

// Validar que el número de identidad tenga exactamente 10 dígitos
if (strlen($numero_identidad) !== 10) {
    echo "<script>
        alert('❌ El número de identidad debe tener exactamente 10 dígitos.');
        window.history.back();
    </script>";
    exit;
}

// Validación de la contraseña
if ($contrasena !== $validacion) {
    echo "<script>
        alert('❌ Las contraseñas no coinciden.');
        window.history.back();
    </script>";
    exit;
}

// Expresión regular para validar la contraseña
$patron_contrasena = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
if (!preg_match($patron_contrasena, $contrasena)) {
    echo "<script>
        alert('❌ La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, números y caracteres especiales.');
        window.history.back();
    </script>";
    exit;
}

// Validación de correo electrónico
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
        alert('❌ El correo electrónico no es válido.');
        window.history.back();
    </script>";
    exit;
}

// Validación de número de teléfono (solo números y 10 dígitos)
if (!preg_match('/^\d{10}$/', $telefono)) {
    echo "<script>
        alert('❌ El número de teléfono debe tener 10 dígitos.');
        window.history.back();
    </script>";
    exit;
}

// Validación de tipo de sangre
$tipos_sangre_validos = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
if (!in_array($tipo_sangre, $tipos_sangre_validos)) {
    echo "<script>
        alert('❌ El tipo de sangre no es válido.');
        window.history.back();
    </script>";
    exit;
}

// Verificar si ya existe el número de identidad
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
        window.location.href = '../super.menu.html';
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
