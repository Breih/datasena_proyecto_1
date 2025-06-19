<?php
// Depuración: Verificar los datos recibidos
var_dump($_POST); // Esto imprimirá los datos del formulario recibidos.

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "datasenn_db");

// Verificar si hubo error de conexión
if ($conexion->connect_error) {
    die("❌ Error de conexión: " . $conexion->connect_error);
}

// Recolección de datos del formulario
$nombre_programa = trim($_POST['nombre_programa'] ?? '');
$codigo_programa = trim($_POST['codigo_programa'] ?? '');
$nivel_formacion = trim($_POST['nivel_formacion'] ?? '');
$estado = trim($_POST['estado'] ?? '');

// Verificar si los campos están vacíos
if (empty($nombre_programa) || empty($codigo_programa) || empty($nivel_formacion) || empty($estado)) {
    echo "<script>alert('❌ Todos los campos son obligatorios.'); window.history.back();</script>";
    exit;
}

// Depuración adicional: Verificar valores de las variables antes de continuar
var_dump($nombre_programa, $codigo_programa, $nivel_formacion, $estado); // Esto te ayudará a ver si las variables están correctas.

// Verificar si el código del programa ya existe
$verificar_sql = "SELECT id FROM programas WHERE codigo_programa = ?";
$verificar_stmt = $conexion->prepare($verificar_sql);
$verificar_stmt->bind_param("s", $codigo_programa);
$verificar_stmt->execute();
$verificar_stmt->store_result();

// Si el código ya existe, mostrar un mensaje y volver
if ($verificar_stmt->num_rows > 0) {
    echo "<script>alert('❌ El código del programa ya está registrado.'); window.history.back();</script>";
    exit;
}
$verificar_stmt->close();

// Consulta SQL preparada para insertar el programa
$sql = "INSERT INTO programas (nombre_programa, codigo_programa, nivel_formacion, estado)
        VALUES (?, ?, ?, ?)";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
if ($stmt === false) {
    die('❌ Error en la preparación de la consulta: ' . $conexion->error);
}

// Usar bind_param con los tipos correctos:
// 's' para string (VARCHAR)
$stmt->bind_param("ssss", $nombre_programa, $codigo_programa, $nivel_formacion, $estado);

// Ejecutar y verificar resultado
if ($stmt->execute()) {
    echo "<script>
        alert('✅ Programa registrado con éxito.');
        window.location.href = '../../../SU_admin/menu_SU_admin/super.menu.html';
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
