<?php
$datos = null;
$documento = $_GET['documento'] ?? '';

if ($documento) {
    try {
        $conexion = new PDO("mysql:host=localhost;dbname=datasenn_db", "root", "");
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE numero_identidad = ?");
        $stmt->execute([$documento]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Información del Aprendiz</title>
    <link rel="stylesheet" href="estilos_ver_aprendiz.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 2rem;
        }
        h1 {
            color: #007bff;
        }
        .contenedor {
            max-width: 600px;
            margin: auto;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 1rem 2rem;
            background: #f9f9f9;
        }
        .info {
            margin-bottom: 1rem;
        }
        label {
            font-weight: bold;
            display: block;
        }
        .volver {
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>Información del Aprendiz</h1>

        <?php if ($datos): ?>
            <div class="info"><label>Nombre completo:</label> <?= htmlspecialchars($datos['nombre_completo']) ?></div>
            <div class="info"><label>Tipo documento:</label> <?= htmlspecialchars($datos['tipo_documento']) ?></div>
            <div class="info"><label>Número de identidad:</label> <?= htmlspecialchars($datos['numero_identidad']) ?></div>
            <div class="info"><label>Correo:</label> <?= htmlspecialchars($datos['correo']) ?></div>
            <div class="info"><label>Teléfono:</label> <?= htmlspecialchars($datos['telefono']) ?></div>
            <div class="info"><label>Residencia:</label> <?= htmlspecialchars($datos['residencia']) ?></div>
            <div class="info"><label>Tipo de sangre:</label> <?= htmlspecialchars($datos['tipo_sangre']) ?></div>
            <div class="info"><label>Estado:</label> <?= htmlspecialchars($datos['estado']) ?></div>
            <div class="info"><label>Número ficha:</label> <?= htmlspecialchars($datos['numero_ficha']) ?></div>
        <?php else: ?>
            <p>❌ No se encontró información para este aprendiz.</p>
        <?php endif; ?>

        <div class="volver">
            <button onclick="window.history.back()">← Volver</button>
        </div>
    </div>
</body>
</html>
