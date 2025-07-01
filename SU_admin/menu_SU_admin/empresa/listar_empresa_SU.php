<?php
// Conexión a la base de datos
try {
    $conexion = new PDO("mysql:host=localhost;dbname=datasenn_db", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Si se ha enviado una búsqueda
$searchQuery = '';
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
}

// Consulta para obtener las empresas, con búsqueda si es necesario
$sql = "SELECT id, tipo_documento, numero_identidad, nickname FROM empresas WHERE 
        tipo_documento LIKE :search OR
        numero_identidad LIKE :search OR
        nickname LIKE :search";
$stmt = $conexion->prepare($sql);
$stmt->bindValue(':search', "%$searchQuery%");
$stmt->execute();
$empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conexion = null; // Cerrar la conexión
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Listar Empresas</title>
    <link rel="icon" href="../../../img/Logotipo_Datasena.png" type="image/x-icon" />
    <link rel="stylesheet" href="../../../css/SU_admin/menu_SU_admin/style.css" />
</head>
<body>
<header>DATASENA</header>
<img src="../../../img/logo-sena.png" alt="Logo SENA" class="img" />

<div class="form-container">
    <h2>Listar Empresas</h2>

    <!-- Barra de búsqueda -->
    <div class="search-container">
        <form method="post">
            <input
                type="text"
                id="search-input"
                name="search"
                placeholder="Buscar por tipo documento, número de identidad o nickname"
                value="<?= htmlspecialchars($searchQuery) ?>"
            />
            <button type="submit" class="search-btn">Buscar</button>
        </form>
    </div>

    <?php if (!empty($searchQuery)): ?>
        <div class="user-list">
            <?php if (!empty($empresas)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tipo Documento</th>
                            <th>Número Identidad</th>
                            <th>Nickname</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empresas as $empresa): ?>
                            <tr>
                                <td><?= htmlspecialchars($empresa['tipo_documento']) ?></td>
                                <td><?= htmlspecialchars($empresa['numero_identidad']) ?></td>
                                <td><?= htmlspecialchars($empresa['nickname']) ?></td>
                                <td>
                                    <button class="listar-btn" onclick="openModal(<?= $empresa['id'] ?>)">Visualizar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No se encontraron empresas con ese criterio de búsqueda.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="buttons-container">
        <button class="back_listar" type="button" onclick="window.location.href='../super_menu.html'">Regresar</button>
    </div>
</div>

<footer>
    <a>&copy; Todos los derechos reservados al SENA</a>
</footer>

<!-- Modal para ver información -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="modal-header">Información de la Empresa</div>
        <div class="modal-body" id="modal-body">
            <!-- Información cargada dinámicamente por AJAX -->
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        var modal = document.getElementById("myModal");
        var modalBody = document.getElementById("modal-body");

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "get_empresa.php?id=" + id, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                if (data.error) {
                    modalBody.innerHTML = '<p>' + data.error + '</p>';
                } else {
                    modalBody.innerHTML = `
                        <p><strong>Tipo Documento:</strong> ${data.tipo_documento}</p>
                        <p><strong>Número Identidad:</strong> ${data.numero_identidad}</p>
                        <p><strong>Nickname:</strong> ${data.nickname}</p>
                        <p><strong>Teléfono:</strong> ${data.telefono}</p>
                        <p><strong>Correo:</strong> ${data.correo}</p>
                        <p><strong>Dirección:</strong> ${data.direccion}</p>
                        <p><strong>Actividad Económica:</strong> ${data.actividad_economica}</p>
                        <p><strong>Estado:</strong> ${data.estado}</p>
                    `;
                }
            }
        };
        xhr.send();

        modal.style.display = "block";
    }

    document.querySelector(".close").onclick = function() {
        document.getElementById("myModal").style.display = "none";
    };

    window.onclick = function(event) {
        var modal = document.getElementById("myModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
</script>
</body>
</html>
