<?php
try {
    $conexion = new PDO("mysql:host=localhost;dbname=datasenn_db", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener todas las fichas para el selector
    $stmt_fichas = $conexion->query("SELECT DISTINCT numero_ficha FROM programas ORDER BY numero_ficha ASC");
    $fichas = $stmt_fichas->fetchAll(PDO::FETCH_COLUMN);

    $usuarios = [];
    $ficha_seleccionada = $_GET['ficha'] ?? '';

    if ($ficha_seleccionada) {
        $stmt = $conexion->prepare("SELECT nombre_completo, numero_identidad, correo, telefono FROM usuarios WHERE numero_ficha = ?");
        $stmt->execute([$ficha_seleccionada]);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Aprendices por Ficha</title>
    <link rel="stylesheet" href="estilos_ver_aprendices.css">
</head>
<body>
    <div class="container">
        <h1>📘 Aprendices por Ficha</h1>

        <form method="GET">
            <label for="ficha">Selecciona una ficha:</label>
            <select name="ficha" id="ficha" onchange="this.form.submit()">
                <option value="">-- Seleccionar --</option>
                <?php foreach ($fichas as $ficha): ?>
                    <option value="<?= htmlspecialchars($ficha) ?>" <?= $ficha == $ficha_seleccionada ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ficha) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if ($ficha_seleccionada): ?>
            <h2>Ficha: <?= htmlspecialchars($ficha_seleccionada) ?></h2>
            <?php if ($usuarios): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre completo</th>
                            <th>Número de identidad</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                            <td><?= htmlspecialchars($u['numero_identidad']) ?></td>
                            <td><?= htmlspecialchars($u['correo']) ?></td>
                            <td>
                                <?= htmlspecialchars($u['telefono']) ?>
                                <a href="ver_aprendiz.php?documento=<?= htmlspecialchars($u['numero_identidad']) ?>" class="btn-ver" style="margin-left:12px;">Ver información</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay aprendices registrados en esta ficha.</p>
            <?php endif; ?>
        <?php endif; ?>

        <div class="volver">
            <button onclick="window.location.href='listar_programas.php'">← Volver a Programas</button>
        </div>
    </div>
</body>
</html>
