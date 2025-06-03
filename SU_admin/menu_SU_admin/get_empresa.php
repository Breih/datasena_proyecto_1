<?php
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID no especificado']);
    exit;
}

$id = intval($_GET['id']);

try {
    $conexion = new PDO("mysql:host=localhost;dbname=datasenn_db", "root", "123456");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conexion->prepare("SELECT * FROM empresas WHERE id = ?");
    $stmt->execute([$id]);
    $empresa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$empresa) {
        echo json_encode(['error' => 'Empresa no encontrada']);
    } else {
        echo json_encode($empresa);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
