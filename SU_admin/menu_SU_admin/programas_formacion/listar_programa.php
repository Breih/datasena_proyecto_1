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
$sql = "SELECT id, nombre_programa, numero_ficha, tipo_programa, activacion 
        FROM programas 
        WHERE nombre_programa LIKE :search OR tipo_programa LIKE :search";
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
    <link rel="icon" href="../../../img/Logotipo_Datasena.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../css/SU_admin/menu_SU_admin/style.css">
    <style>
        /* Centrar el header */
        header {
            text-align: center; /* Centra el texto */
            font-size: 2.0rem; /* Tamaño de fuente más grande */
            font-weight: bold;
            margin-top: 20px;
            color: var(--black); /* Color que deseas para el texto */
        }

        /* Estilos para la barra de búsqueda */
        .search-container {
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #search-input {
            width: 93%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-right: 10px;
            margin-bottom: 5px;
        }

        .search-btn {
            padding: 10px 20px;
            background-color: var(--primary-950);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-left: auto;
            margin-top: 1rem;
        }

        .search-btn:hover {
            background-color: var(--primary-800);
        }

        /* Modal */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            padding-top: 60px;
        }

        /* Modal content */
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

        /* Close button */
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

<div class="form-container">
    <h2>Listar Programas</h2>
    <img src="../../../img/logo-sena.png" alt="Logo SENA" class="img">

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
            <th>Código</th>
            <th>Nivel Formación</th>
            <th>activacion</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($programas as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['nombre_programa']) ?></td>
            <td><?= htmlspecialchars($p['numero_ficha']) ?></td>
            <td><?= htmlspecialchars($p['tipo_programa']) ?></td>
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
        <button class="back_listar" type="button" onclick="window.location.href='../super_menu.html'">Regresar</button>
    </div>
</div>

<footer>
    <a>&copy; Todos los derechos reservados al SENA</a>
</footer>

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
