<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "123456", "datasenn_db");

// Verificar si hubo error de conexión
if ($conexion->connect_error) {
    die("❌ Error de conexión: " . $conexion->connect_error);
}

// Recolección de datos del formulario
$nombre_programa = $_POST['nombre_programa'] ?? '';
$tipo_programa = $_POST['tipo_programa'] ?? '';
$numero_ficha = $_POST['numero_ficha'] ?? '';
$duracion_programa = $_POST['duracion_programa'] ?? '';
$activacion = $_POST['activacion'] ?? '';

// Validación básica de campos vacíos
if (
    empty($nombre_programa) ||
    empty($tipo_programa) ||
    empty($numero_ficha) ||
    empty($duracion_programa) ||
    empty($activacion)
) {
    echo "<script>
        alert('❌ Todos los campos son obligatorios.');
        window.history.back();
    </script>";
    exit;
}

// Consulta SQL preparada para insertar el programa
$sql = "INSERT INTO programas (nombre_programa, tipo_programa, numero_ficha, duracion_programa, activacion)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssss", $nombre_programa, $tipo_programa, $numero_ficha, $duracion_programa, $activacion);

// Ejecutar y verificar resultado
if ($stmt->execute()) {
    echo "<script>
        alert('✅ Programa registrado con éxito.');
        window.location.href = '../../SU_admin/menu_SU_admin/super.menu.html';
    </script>";
} else {
    echo "<script>
        alert('❌ Error al registrar el programa: " . addslashes($stmt->error) . "');
        window.history.back();
    </script>";
}

// Cierre de conexión
$stmt->close();
$conexion->close();
?>
