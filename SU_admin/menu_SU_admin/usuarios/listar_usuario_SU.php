<?php
// Conexión a la base de datos
try {
    $conexion = new PDO("mysql:host=localhost;dbname=datasenn_db", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Si se ha enviado una búsqueda
$searchQuery = $_POST['search'] ?? '';

// Consulta para obtener los usuarios
$sql = "SELECT nombre_completo, numero_identidad FROM usuarios 
        WHERE nombre_completo LIKE :search OR numero_identidad LIKE :search";
$stmt = $conexion->prepare($sql);
$stmt->bindValue(':search', "%$searchQuery%");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listar Usuario</title>
    <link rel="stylesheet" href="../../../css/SU_admin/menu_SU_admin/style.css
    ">
    <link rel="icon" href="../../../img/Logotipo_Datasena.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <!-- Línea azul superior con logo del gobierno -->
    <div class="blue-line-top">
        <img src="../../../img/gov.png" alt="gov" class="gov-logo">
    </div>

<header>DATASENA</header>
<img src="../../../img/logo-sena.png" alt="Logo SENA" class="logo-sena">

<div class="form-container">
    <h2>Listar Usuario</h2>

    <!-- Barra de búsqueda -->
    <form method="post" class="search-container">
        <input type="text" name="search" placeholder="Buscar por nombre o documento" value="<?= htmlspecialchars($searchQuery) ?>">
        <button type="submit" class="search-btn">Buscar</button>
    </form>

    <!-- Tabla de resultados -->
    <?php if (!empty($searchQuery)): ?>
        <div class="user-list">
            <?php if (!empty($usuarios)): ?>
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
                                    <button class="listar-btn" onclick="openModal('<?= $usuario['numero_identidad'] ?>')">Visualizar</button>
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
        <button class="back_listar" type="button" onclick="window.location.href='../super_menu.html'">Regresar</button>
    </div>
</div>

        <!-- Línea azul inferior con logo del gobierno -->
        <div class="blue-line-bottom">
            <img class="gov-logo"src="../../../img/gov.png" alt="gov" >
        </div>

<footer>
    <a>&copy; Todos los derechos reservados al SENA</a>
</footer>

<!-- Modal para mostrar información -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="modal-header">Información del Usuario</div>
        <div class="modal-body" id="modal-body">
            <!-- Contenido dinámico cargado por JS -->
        </div>
    </div>
</div>

<script>
    function openModal(numero_identidad) {
        var modal = document.getElementById("myModal");
        var modalBody = document.getElementById("modal-body");

        fetch("get_usuario_visualizar.php?numero_identidad=" + encodeURIComponent(numero_identidad))
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    modalBody.innerHTML = "<p>" + data.error + "</p>";
                } else {
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
                modal.style.display = "block";
            });
    }

    document.querySelector(".close").onclick = function () {
        document.getElementById("myModal").style.display = "none";
    }

    window.onclick = function (event) {
        var modal = document.getElementById("myModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>
