<?php
header('Content-Type: application/json');

try {
    $conexion = new PDO("mysql:host=localhost;dbname=datasenn_db", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $documento = $_POST['documento'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $descripcion = $_POST['contenido'] ?? '';

    if ($documento === '' || $descripcion === '') {
        echo json_encode(['success' => false, 'error' => 'Faltan campos obligatorios (documento o contenido).']);
        exit;
    }

    if ($fecha === '') {
        // Inserta sin fecha, la BD usará current_timestamp()
        $sql = "INSERT INTO reportes (numero_identidad, descripcion) VALUES (:documento, :descripcion)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':descripcion', $descripcion);
    } else {
        // Inserta con fecha especificada
        $sql = "INSERT INTO reportes (numero_identidad, descripcion, fecha_reporte) VALUES (:documento, :descripcion, :fecha)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':fecha', $fecha);
    }

    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
