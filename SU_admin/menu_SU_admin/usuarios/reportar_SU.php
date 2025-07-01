<?php
$nombre_usuario = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])) {
    $documento = trim($_POST['documento']);

    if ($documento !== "") {
        try {

            $conexion = new PDO("mysql:host=localhost;dbname=datasenn_db", "root", "");
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT nombre_completo FROM usuarios WHERE numero_identidad = :documento LIMIT 1";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':documento', $documento);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                $nombre_usuario = $resultado['nombre_completo'];
            } else {
                $nombre_usuario = "No encontrado";
            }
        } catch (PDOException $e) {
            $nombre_usuario = "Error en la consulta";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reporte</title>
    <link rel="icon" href="../../../img/Logotipo_Datasena.png" type="image/x-icon" />
    <link rel="stylesheet" href="../../../css/admin/style.css" />
</head>
<body>
        <!-- Línea azul superior con logo del gobierno -->
        <div class="blue-line-top">
            <img src="../../../img/gov.png" alt="gov" class="gov-logo">
        </div>
        
    <header>DATASENA</header>

    <div class="logo-container">
        <img src="../../../img/logo-sena.png" alt="Logo" />
    </div>

    <div class="form-container">
        <div class="report_box">
            <h2 class="report__subtitle">Reporte</h2>
            <form class="report__form" id="formReporte" method="POST">
                <div class="form_group">
                    <label for="documento">Buscar por documento</label>
                    <input 
                        type="text" 
                        id="documento" 
                        name="documento" 
                        placeholder="Número de documento" 
                        required
                        value="<?= isset($_POST['documento']) ? htmlspecialchars($_POST['documento']) : '' ?>"
                    />
                    <button type="" name="buscar" class="btn-bus">Buscar</button>
                </div>

                <div class="form_group">
                    <label for="nombre">Nombre</label>
                    <input 
                        type="text" 
                        id="nombre" 
                        name="nombre" 
                        placeholder="Nombre completo" 
                        readonly
                        value="<?= htmlspecialchars($nombre_usuario) ?>"
                    />
                </div>

                <div class="form_group">
                    <label for="fecha">Fecha</label>
                    <input type="date" id="fecha" name="fecha" />
                </div>

                <div class="form_group" style="grid-column: 1 / -1;">
                    <label for="contenido">Contenido</label>
                    <textarea id="contenido" name="contenido" placeholder="Escribe aquí..."></textarea>
                </div>

                <div class="button">
                    <button type="button" id="btnReportar" class="btn-submit">Reportar</button>
                    <button class="logout-btn" type="button" onclick="window.location.href='../super_menu.html'">Regresar</button>
                </div>
            </form>
        </div>
    </div>

        <!-- Línea azul inferior con logo del gobierno -->
        <div class="blue-line-bottom">
            <img class="gov-logo"src="../../../img/gov.png" alt="gov" >
        </div>

    <footer class="footer">
        <a>&copy; Todos los derechos reservados al SENA</a>
    </footer>

    <script>
        document.getElementById("btnReportar").addEventListener("click", function () {
            const documento = document.getElementById("documento").value.trim();
            const fecha = document.getElementById("fecha").value.trim();
            const contenido = document.getElementById("contenido").value.trim();

            if (documento === "" || contenido === "") {
                alert("Por favor completa los campos obligatorios: documento y contenido.");
                return;
            }

            const formData = new FormData();
            formData.append("documento", documento);
            formData.append("fecha", fecha);
            formData.append("contenido", contenido);

            fetch("guardar_reporte.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("✅ Reporte guardado correctamente.");
                    document.getElementById("formReporte").reset();
                    document.getElementById("nombre").value = "";
                } else {
                    alert("❌ Error: " + data.error);
                }
            })
            .catch(error => {
                alert("❌ Error en la comunicación con el servidor.");
                console.error(error);
            });
        });
    </script>
</body>
</html>
