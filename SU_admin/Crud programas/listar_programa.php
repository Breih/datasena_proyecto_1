<?php
// Conexión
try {
    $conexion = new PDO("mysql:host=localhost;dbname=datasenn_db", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Búsqueda
$searchQuery = '';
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
}

// Consulta con búsqueda en nombre_programa y tipo_programa
$sql = "SELECT id, nombre_programa, tipo_programa, numero_ficha, duracion_programa, activacion FROM programas 
        WHERE nombre_programa LIKE :search LIKE :search";
$stmt = $conexion->prepare($sql);
$stmt->bindValue(':search', "%$searchQuery%");
$stmt->execute();
$programas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conexion = null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listar Programas</title>
    <link rel="stylesheet" href="../../css/SU_admin/menu_SU_admin/style.css">
    <style>
        header { text-align: center; font-size: 2.5rem; font-weight: bold; margin-top: 20px; color: var(--black); }
        .search-container { text-align: center; margin-bottom: 20px; display: flex; justify-content: center; }
        #search-input { width: 60%; padding: 10px; font-size: 16px; border-radius: 25px; border: 1px solid #ccc; margin-right: 10px; }
        .search-btn { padding: 10px 20px; background-color: var(--primary-950); color: white; border: none; border-radius: 25px; cursor: pointer; }
        .search-btn:hover { background-color: var(--primary-800); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        .modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); }
        .modal-content { background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 10px; width: 50%; box-shadow: 0 4px 8px rgba(0,0,0,0.3); }
        .close { float: right; font-size: 28px; cursor: pointer; }
    </style>
</head>
<body>
<header>DATASENA</header>

<div class="form-container">
    <h2>Listar Programas</h2>

    <div class="search-container">
        <form method="post">
            <input type="text" id="search-input" name="search" placeholder="Buscar por nombre o tipo" value="<?= htmlspecialchars($searchQuery) ?>">
            <button type="submit" class="search-btn">Buscar</button>
        </form>
    </div>

    <?php if (!empty($searchQuery)): ?>
        <?php if (!empty($programas)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Número Ficha</th>
                        <th>Duración</th>
                        <th>Activación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($programas as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nombre_programa']) ?></td>
                        <td><?= htmlspecialchars($p['tipo_programa']) ?></td>
                        <td><?= htmlspecialchars($p['numero_ficha']) ?></td>
                        <td><?= htmlspecialchars($p['duracion_programa']) ?></td>
                        <td><?= htmlspecialchars($p['activacion']) ?></td>
                        <td><button onclick="openModal(<?= $p['id'] ?>)">Visualizar</button></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron programas.</p>
        <?php endif; ?>
    <?php endif; ?>

    <div class="buttons-container">
        <button class="back_listar" type="button" onclick="window.location.href='super.menu.html'">Regresar</button>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="modal-header">Información del Programa</div>
        <div class="modal-body" id="modal-body"></div>
    </div>
</div>

<script>
    function openModal(id) {
        const modal = document.getElementById("myModal");
        const modalBody = document.getElementById("modal-body");

        const xhr = new XMLHttpRequest();
        xhr.open("GET", "get_programa.php?id=" + id, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                if (data.error) {
                    modalBody.innerHTML = '<p>' + data.error + '</p>';
                } else {
                    modalBody.innerHTML = `
                        <p><strong>Nombre:</strong> ${data.nombre_programa}</p>
                        <p><strong>Tipo:</strong> ${data.tipo_programa}</p>
                        <p><strong>Número Ficha:</strong> ${data.numero_ficha}</p>
                        <p><strong>Duración:</strong> ${data.duracion_programa}</p>
                        <p><strong>Activación:</strong> ${data.activacion}</p>
                    `;
                }
                modal.style.display = "block";
            }
        };
        xhr.send();
    }

    document.querySelector(".close").onclick = function() {
        document.getElementById("myModal").style.display = "none";
    };

    window.onclick = function(event) {
        const modal = document.getElementById("myModal");
        if (event.target === modal) modal.style.display = "none";
    };
</script>
</body>
</html>
