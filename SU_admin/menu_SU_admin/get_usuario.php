<?php
// Conexión a la base de datos
try {
    $conexion = new PDO("mysql:host=localhost;dbname=datasenn_db", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Obtener el número de identidad desde la solicitud AJAX
if (isset($_GET['numero_identidad'])) {
    $numero_identidad = $_GET['numero_identidad'];

    // Consulta para obtener los detalles del usuario
    $sql = "SELECT nombre_completo, tipo_documento, numero_identidad, residencia, tipo_sangre, correo, telefono, estado FROM usuarios WHERE numero_identidad = :numero_identidad";
    $stmt = $conexion->prepare($sql);
    $stmt->bindValue(':numero_identidad', $numero_identidad);
    $stmt->execute();

    // Obtener los resultados
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cerrar la conexión
    $conexion = null;

    // Verificar si el usuario existe
    if ($usuario) {
        echo json_encode($usuario); // Enviar los datos en formato JSON
    } else {
        echo json_encode(['error' => 'Usuario no encontrado']);
    }
} else {
    echo json_encode(['error' => 'Número de identidad no proporcionado']);
}
?>
