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
$sql = "SELECT id, tipo_documento, nickname FROM empresas WHERE 
        tipo_documento LIKE :search OR   
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
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Listar empresas</title>
    <link rel="icon" href="../../img/Logotipo_Datasena.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/listar_empresas.css"/>
    <style>

                    /* Variables y estilos globales */
            :root {
                --primary-950: #39A900;
                --primary-900: #4db21a;
                --primary-800: #61BA33;
                --primary-700: #74c34d;
                --primary-600: #88cb66;
                --primary-500: #9cd480;
                --primary-400: #b0dd99;
                --primary-300: #c4e5b3;
                --primary-200: #d7eecc;

                --secundary-1-950: #007832;
                --secundary-1-900: #1A8647;
                --secundary-1-800: #33935B;
                --secundary-1-700: #4DA170;
                --secundary-1-600: #66AE84;
                --secundary-1-500: #80BC99;
                --secundary-1-400: #99C9AD;
                --secundary-1-300: #B3D7C2;
                --secundary-1-200: #cce4d6;

                --secundary-2-950: #71277a;
                --secundary-2-900: #7f3d86;
                --secundary-2-800: #8d5295;
                --secundary-2-700: #9c68a2;
                --secundary-2-600: #aa7daf;
                --secundary-2-500: #b893bd;
                --secundary-2-400: #c6a9ca;
                --secundary-2-300: #dabed7;
                --secundary-2-200: #e3daea;

                --secundary-3-950: #00304d;
                --secundary-3-900: #1a455f;
                --secundary-3-800: #335971;
                --secundary-3-700: #4d6e82;
                --secundary-3-600: #668394;
                --secundary-3-500: #8098a6;
                --secundary-3-400: #99acb8;
                --secundary-3-300: #b3c1ca;
                --secundary-3-200: #ccd6db;

                --secondary-4-950: #fdc300;
                --secondary-4-900: #fdc91a;
                --secondary-4-800: #fdcf33;
                --secondary-4-700: #fed54d;
                --secondary-4-600: #fedb66;
                --secondary-4-500: #fee180;
                --secondary-4-400: #fee799;
                --secondary-4-300: #feedb3;
                --secondary-4-200: #fff3cc;

                --white: #ffffff;
                --black: #000000;
                --gray-950: #f6f6f6;
                --gray-900: #e1e1e1;
                --gray-800: #cccccc;
                --gray-700: #b7b7b7;
                --gray-600: #a2a2a2;
                --gray-500: #8d8d8d;

                --main-font: Arial, sans-serif;
                --font-size-h1-plus: 4.8rem;
                --font-size-h1: 3.2rem;
                --font-size-h2: 2.4rem;
                --font-size-h3: 2.0rem;
                --font-size-h4: 1.6rem;
                --font-size-h5: 1.4rem;
                --font-size-h6: 1.2rem;
            }

            body {
                margin: 0;
                font-family: var(--main-font);
                background-color: var(--white);
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }

            header {
                padding: 30px;
                text-align: center;
                font-size: 2.0rem;
                font-weight: bold;
                margin-top: 20px;
                color: var(--black);
            }

            header .logo {
                position: absolute;
                top: 5px;
                left: 5px;
                width: 40px;
                height: auto;
            }

            .logo-sena {
                padding: 35px;
                position: absolute;
                top: 10px;
                right: 10px;
                width: 80px;
                height: auto;
            }

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
                background-color: var(--secundary-2-500);
            }

            .user-list {
                margin-top: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin: 0;
            }

            th, td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #f2f2f2;
                font-weight: bold;
                gap: 20px;
            }

            tr:hover {
                background-color: #f9f9f9;
            }

            .delete-btn {
                background-color: var(--secundary-2-950);
                color: var(--white);
                border: none;
                border-radius: 4px;
                padding: 8px 16px;
                font-size: 14px;
                cursor: pointer;
            }

            .delete-btn:hover {
                background-color: var(--secundary-2-900);
            }

            footer {
                position: fixed;
                bottom: 40px;
                left: 0;
                width: 100%;
                background-color: var(--primary-950);
                color: var(--white);
                text-align: center;
                font-size: 1.0rem;
                padding: 5px 0;
                z-index: 1500;
            }

            h1 {
                text-align: center;
                margin-bottom: 30px;
                font-size: var(--font-size-h3);
                padding-bottom: 15px;
            }

            .form-container {
                max-width: 1000px;
                margin: 15px auto;
                padding: 30px;
                border-radius: 1.2rem;
                background-color: var(--secundary-3-200);
                box-shadow: 0 3px 6px var(--secundary-3-950);
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            h2 {
                text-align: center;
                margin-bottom: 25px;
                font-size: var(--font-size-h3);
                border-bottom: 1px solid #ddd;
                padding-bottom: 12px;
            }

            .form-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                width: 100%;
            }

            .form-row {
                display: flex;
                flex-direction: column;
            }

            .form-row label {
                font-weight: bold;
                color: #333;
                margin-bottom: 5px;
                font-size: 14px;
            }

            input {
                padding: 10px;
                font-size: 14px;
                border-radius: 4px;
                border: 1px solid #ccc;
                width: 95%;
            }

            textarea {
                padding: 10px;
                font-size: 14px;
                border-radius: 4px;
                border: 1px solid #ccc;
                width: 95%;
            }

            .button {
                grid-column: 1 / -1;
                display: flex;
                justify-content: center;
                gap: 15px;
                margin-top: 20px;
            }

            .btn-submit,
            .logout-btn {
                background-color: var(--primary-950);
                color: var(--white);
                border: none;
                border-radius: 4px;
                padding: 8px 18px;
                font-size: 1.0rem;
                min-width: 120px;
                transition: background-color 0.3s;
            }

            .btn-submit:hover,
            .logout-btn:hover {
                background-color: var(--secundary-2-500);
            }

            .back_listar, .listar-btn, .view-btn {
                background-color: var(--primary-950);
                color: var(--white);
                border: none;
                border-radius: 4px;
                padding: 8px 16px;
                margin: 5px;
                font-size: 14px;
                cursor: pointer;
            }

            .back_listar:hover, .listar-btn:hover, .view-btn:hover {
                background-color: var(--secondary-2-500);
            }

            @media screen and (max-width: 768px) {
                .form-grid {
                    grid-template-columns: 1fr;
                    gap: 15px;
                }

                .form-row label {
                    font-size: 12px;
                }

                input {
                    font-size: 12px;
                }

                .buttons-container {
                    flex-direction: column;
                    align-items: center;
                }
            }

            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
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

            .blue-line-top {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 40px;
                background-color: var(--secundary-3-950);
                z-index: 2000;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }

            .blue-line-bottom {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 40px;
                background-color: var(--secundary-3-950);
                z-index: 2000;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.2);
            }

            .gov-logo {
                padding: 0%;
                height: 20px;
                object-fit: contain;
            }

    </style>
</head>
<body>

    <div class="blue-line-top">
        <img src="../../img/gov.png" alt="gov" class="gov-logo">
    </div>

<header>DATASENA</header>
<img src="../../img/logo-sena.png" alt="Logo SENA" class="logo-sena" />

<div class="form-container">
    <h2>Listar empresas</h2>

    <!-- Barra de búsqueda -->
    <div class="search-container">
        <form method="post">
            <input
                type="text"
                id="search-input"
                name="search"
                placeholder="Buscar por tipo documento o nickname"
                value="<?= htmlspecialchars($searchQuery) ?>"
            />
            <button type="submit" class="search-btn">Buscar</button>
        </form>
    </div>

    <?php if (isset($searchQuery) && !empty($searchQuery)): ?>
        <div class="user-list">
            <?php if (isset($empresas[0])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tipo Documento</th>
                            <th>Nickname</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empresas as $empresas): ?>
                            <tr>
                                <td><?= htmlspecialchars($empresas['tipo_documento']) ?></td>
                                <td><?= htmlspecialchars($empresas['nickname']) ?></td>
                                <td>
                                    <button class="listar-btn" onclick="openModal(<?= $empresas['id'] ?>)">Visualizar</button>
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
        <button class="back_listar" type="button" onclick="window.location.href='../admin.menu.html'">Regresar</button>
    </div>
</div>

    <div class="blue-line-bottom">
        <img class="gov-logo" src="../../img/gov.png" alt="gov">
    </div>

<footer>
    <a>&copy; Todos los derechos reservados al SENA</a>
</footer>

<!-- Modal para ver información -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="modal-header">Información de <br>la empresas</div>
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
        xhr.open("GET", "get_empresas.php?id=" + id, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);

                if (data.error) {
                    modalBody.innerHTML = '<p>' + data.error + '</p>';
                } else {
                    modalBody.innerHTML = `
                        <p><strong>Tipo Documento:</strong> ${data.tipo_documento}</p>
                        <p><strong>Nickname:</strong> ${data.nickname}</p>
                        <p><strong>Teléfono:</strong> ${data.telefono}</p>
                        <p><strong>Correo:</strong> ${data.correo}</p>
                        <p><strong>Dirección:</strong> ${data.direccion}</p>
                        <p><strong>Rol:</strong> ${data.rol}</p>
                        <p><strong>Actividad Económica:</strong> ${data.actividad_economica}</p>
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