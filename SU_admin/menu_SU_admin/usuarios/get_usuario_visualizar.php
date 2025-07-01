<?php
header('Content-Type: application/json'); // <- AÑADIDO

try {
    $conexion = new PDO("mysql:host=localhost;dbname=datasenn_db", "root", ""); // Usa la misma clave
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
    exit;
}

if (isset($_GET['numero_identidad'])) {
    $numero_identidad = $_GET['numero_identidad'];

    $sql = "SELECT nombre_completo, tipo_documento, numero_identidad, residencia, tipo_sangre, correo, telefono, estado 
            FROM usuarios 
            WHERE numero_identidad = :numero_identidad";
    $stmt = $conexion->prepare($sql);
    $stmt->bindValue(':numero_identidad', $numero_identidad);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    $conexion = null;

    if ($usuario) {
        echo json_encode($usuario);
    } else {
        echo json_encode(['error' => 'Usuario no encontrado']);
    }
} else {
    echo json_encode(['error' => 'Número de identidad no proporcionado']);
}
?>
