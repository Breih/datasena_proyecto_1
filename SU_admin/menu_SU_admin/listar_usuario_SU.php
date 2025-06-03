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

// Consulta para obtener los usuarios, con búsqueda si es necesario
$sql = "SELECT nombre_completo, numero_identidad FROM usuarios WHERE nombre_completo LIKE :search OR numero_identidad LIKE :search";
$stmt = $conexion->prepare($sql);
$stmt->bindValue(':search', "%$searchQuery%");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conexion = null; // Cerrar la conexión
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Usuario</title>
    <link rel="icon" href="../img/Logotipo_Datasena.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/SU_admin/menu_SU_admin/style.css">
    <style>
        /* Centrar el header */
        header {
            text-align: center; /* Centra el texto */
            font-size: 2.5rem; /* Tamaño de fuente más grande */
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
            width: 60%;
            padding: 10px;
            font-size: 16px;
            border-radius: 25px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
    <img src="../../img/logo-sena.png" alt="Logo SENA" class="img">

    <div class="form-container">
        <h2>Listar Usuario</h2>
        
        <!-- Barra de búsqueda -->
        <div class="search-container">
            <form method="post">
                <input type="text" id="search-input" name="search" placeholder="Buscar por nombre o documento" value="<?= htmlspecialchars($searchQuery) ?>">
                <button type="submit" class="search-btn">Buscar</button>
            </form>
        </div>

        <?php if (isset($searchQuery) && !empty($searchQuery)): ?>
            <!-- Mostrar tabla solo si hay búsqueda -->
            <div class="user-list">
                <?php if (isset($usuarios[0])): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre Completo</th>
                                <th>Número de Documento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= htmlspecialchars($usuario['nombre_completo']) ?></td>
                                    <td><?= htmlspecialchars($usuario['numero_identidad']) ?></td>
                                    <td>
                                        <!-- Botón de visualizar con el estilo adecuado -->
                                        <button class="listar-btn" onclick="openModal(<?= $usuario['numero_identidad'] ?>)">Visualizar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No se encontraron usuarios con ese criterio de búsqueda.</p>
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
            <div class="modal-header">Información del Usuario</div>
            <div class="modal-body" id="modal-body">
                <!-- Información cargada dinámicamente por AJAX -->
            </div>
        </div>
    </div>

    <script>
        function openModal(numero_identidad) {
            var modal = document.getElementById("myModal");
            var modalBody = document.getElementById("modal-body");

            // Realizar una solicitud AJAX para obtener la información del usuario
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_usuario.php?numero_identidad=" + numero_identidad, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);

                    // Verificar si hay un error o si se obtuvo la información
                    if (data.error) {
                        modalBody.innerHTML = '<p>' + data.error + '</p>';
                    } else {
                        // Mostrar los datos en el modal
                        modalBody.innerHTML = `
                            <p><strong>Nombre Completo:</strong> ${data.nombre_completo}</p>
                            <p><strong>Tipo de Documento:</strong> ${data.tipo_documento}</p>
                            <p><strong>Residencia:</strong> ${data.residencia}</p>
                            <p><strong>Correo:</strong> ${data.correo}</p>
                            <p><strong>Teléfono:</strong> ${data.telefono}</p>
                            <p><strong>Tipo de Sangre:</strong> ${data.tipo_sangre}</p>
                            <p><strong>Estado:</strong> ${data.estado}</p>
                        `;
                    }
                }
            };
            xhr.send();

            // Mostrar el modal
            modal.style.display = "block";
        }

        // Cerrar el modal cuando se hace clic en la "X"
        document.querySelector(".close").onclick = function() {
            document.getElementById("myModal").style.display = "none";
        }

        // Cerrar el modal si se hace clic fuera de él
        window.onclick = function(event) {
            var modal = document.getElementById("myModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body
</html>