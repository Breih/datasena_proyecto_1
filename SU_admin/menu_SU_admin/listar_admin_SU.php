<?php
// Conexión a la base de datos
try {
    $conexion = new PDO("mysql:host=localhost;dbname=datasenn_db", "root", "123456");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Si se ha enviado una búsqueda
$searchQuery = '';
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
}

// Consulta para obtener los administradores, con búsqueda si es necesario
$sql = "SELECT id, tipo_documento, nombres, apellidos, correo_electronico FROM admin WHERE 
        numero_documento LIKE :search OR 
        nombres LIKE :search OR 
        correo_electronico LIKE :search";
$stmt = $conexion->prepare($sql);
$stmt->bindValue(':search', "%$searchQuery%");
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conexion = null; // Cerrar la conexión
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Listar Administradores</title>
    <link rel="icon" href="../img/Logotipo_Datasena.png" type="image/x-icon" />
    <link rel="stylesheet" href="../../css/SU_admin/menu_SU_admin/style.css" />
    <style>
        /* Puedes mantener el mismo estilo que usaste para empresas */
        header {
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            margin-top: 20px;
            color: var(--black);
        }
        .search-container {
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #search-input {
            width: 60%;
            padding: 10px;
            font-size: 16px;
            border-radius: 25px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-right: 10px;
        }
        .search-btn {
            padding: 10px 20px;
            background-color: var(--primary-950);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-btn:hover {
            background-color: var(--primary-800);
        }
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0; top: 0;
            width: 100%; height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .modal-header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .modal-body {
            font-size: 16px;
        }
    </style>
</head>
<body>
<header>DATASENA</header>
<img src="../../img/logo-sena.png" alt="Logo SENA" class="img" />

<div class="form-container">
    <h2>Listar Administradores</h2>

    <!-- Barra de búsqueda -->
    <div class="search-container">
        <form method="post">
            <input
                type="text"
                id="search-input"
                name="search"
                placeholder="Buscar por documento, nombres o correo_electronico"
                value="<?= htmlspecialchars($searchQuery) ?>"
            />
            <button type="submit" class="search-btn">Buscar</button>
        </form>
    </div>

    <?php if (isset($searchQuery) && !empty($searchQuery)): ?>
        <div class="user-list">
            <?php if (isset($admins[0])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Documento</th>
                            <th>nombres</th>
                            <th>apellidos</th>
                            <th>correo_electronico</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($admins as $admin): ?>
                            <tr>
                                <td><?= htmlspecialchars($admin['tipo_documento']) ?></td>
                                <td><?= htmlspecialchars($admin['nombres']) ?></td>
                                <td><?= htmlspecialchars($admin['apellidos']) ?></td>
                                <td><?= htmlspecialchars($admin['correo_electronico']) ?></td>
                                <td>
                                    <button class="listar-btn" onclick="openModal(<?= $admin['id'] ?>)">Visualizar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No se encontraron administradores con ese criterio de búsqueda.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="buttons-container">
        <button class="back_listar" type="button" onclick="window.location.href='super.menu.html'">Regresar</button>
    </div>
</div>

<footer>
    <a>&copy; Todos los derechos reservados al SENA</a>
</footer>

<!-- Modal para ver información -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="modal-header">Información del Administrador</div>
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
        xhr.open("GET", "get_admin.php?id=" + id, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);

                if (data.error) {
                    modalBody.innerHTML = '<p>' + data.error + '</p>';
                } else {
                    modalBody.innerHTML = `
                        <p><strong>tipo_documento:</strong> ${data.tipo_documento}</p>
                        <p><strong>nombres:</strong> ${data.nombres}</p>
                        <p><strong>apellidos:</strong> ${data.apellidos}</p>
                        <p><strong>correo_electronico:</strong> ${data.correo_electronico}</p>
                        <p><strong>Rol:</strong> ${data.rol}</p>
                    `;
                }
            }
        };
        xhr.send();

        modal.style.display = "block";
    }

    // Cerrar el modal al hacer clic en la X
    document.querySelector(".close").onclick = function() {
        document.getElementById("myModal").style.display = "none";
    };

    // Cerrar modal si se hace clic fuera de él
    window.onclick = function(event) {
        var modal = document.getElementById("myModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
</script>
</body>
</html>
