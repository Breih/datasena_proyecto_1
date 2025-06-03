<?php
include("diagnostico_empresarial_conexion.php");

$mensaje_exito = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $empresa = $_POST["empresa"];
    $nit = $_POST["nit"];
    $sector = $_POST["sector"];
    $tamano = $_POST["tamano"];
    $ubicacion = $_POST["ubicacion"];
    $empleados = $_POST["empleados"];
    $contrataciones = $_POST["contrataciones"];
    $contrato_frecuente = $_POST["contrato_frecuente"];
    $tiene_proceso = $_POST["tiene_proceso"];
    $perfiles_definidos = $_POST["perfiles_definidos"];
    $publicacion = $_POST["publicacion"];
    $aprendices = $_POST["aprendices"];
    $programa_apoyo = $_POST["programa_apoyo"];
    $perfiles_necesarios = $_POST["perfiles_necesarios"];
    $infraestructura = $_POST["infraestructura"];
    $apoyo_seleccion = $_POST["apoyo_seleccion"];
    $beneficios = $_POST["beneficios"];

    $sql = "INSERT INTO diagnostico_empresarial (
        empresa, nit, sector, tamano, ubicacion,
        empleados, contrataciones, contrato_frecuente,
        tiene_proceso, perfiles_definidos, publicacion,
        aprendices, programa_apoyo, perfiles_necesarios,
        infraestructura, apoyo_seleccion, beneficios
    ) VALUES (
        '$empresa', '$nit', '$sector', '$tamano', '$ubicacion',
        '$empleados', '$contrataciones', '$contrato_frecuente',
        '$tiene_proceso', '$perfiles_definidos', '$publicacion',
        '$aprendices', '$programa_apoyo', '$perfiles_necesarios',
        '$infraestructura', '$apoyo_seleccion', '$beneficios'
    )";

    if ($conn->query($sql) === TRUE) {
        $mensaje_exito = true;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico Empresarial</title>
    <link rel="icon" href="../img/Logotipo_Datasena.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/empresa/diagnostico_empresarial.css">
</head>
<body>
    <header>DATASENA</header>
    <img src="../img/logo-sena.png" alt="Logo SENA" class="img">
    <div class="forma-container">
        <h4>Formulario de Diagnóstico Empresarial</h4>
        <form class="forma-grid" method="POST">
            <!-- Sección 1: Información General -->
            <div class="forma-row">
                <label for="empresa">Nombre de la empresa:</label>
                <input type="text" id="empresa" name="empresa" required>
            </div>
            <div class="forma-row">
                <label for="nit">NIT:</label>
                <input type="text" id="nit" name="nit" required>
            </div>
            <div class="forma-row">
                <label for="sector">Sector económico:</label>
                <select id="sector" name="sector" required>
                    <option>Agroindustria</option>
                    <option>Comercio</option>
                    <option>Tecnología</option>
                    <option>Servicios</option>
                    <option>Otros</option>
                </select>
            </div>
            <div class="forma-row">
                <label for="tamano">Tamaño:</label>
                <select id="tamano" name="tamano" required>
                    <option>Microempresa (1-10)</option>
                    <option>Pequeña empresa (11-50)</option>
                    <option>Mediana empresa (51-200)</option>
                    <option>Grande (&gt;200)</option>
                </select>
            </div>
            <div class="forma-row">
                <label for="ubicacion">Ubicación:</label>
                <input type="text" id="ubicacion" name="ubicacion" required>
            </div>

            <!-- Sección 2: Talento humano -->
            <div class="forma-row">
                <label for="empleados">Total de empleados:</label>
                <input type="number" id="empleados" name="empleados" required>
            </div>
            <div class="forma-row">
                <label for="contrataciones">Contrataciones último año:</label>
                <input type="number" id="contrataciones" name="contrataciones" required>
            </div>
            <div class="forma-row">
                <label for="contrato_frecuente">Tipo de contrato frecuente:</label>
                <select id="contrato_frecuente" name="contrato_frecuente" required>
                    <option>Fijo</option>
                    <option>Indefinido</option>
                    <option>Prestación de servicios</option>
                    <option>Aprendices SENA</option>
                </select>
            </div>

            <!-- Sección 3: Proceso de contratación -->
            <div class="forma-row">
                <label for="tiene_proceso">Proceso de selección formal:</label>
                <select id="tiene_proceso" name="tiene_proceso" required>
                    <option>Sí</option>
                    <option>No</option>
                </select>
            </div>
            <div class="forma-row">
                <label for="perfiles_definidos">Perfiles definidos:</label>
                <select id="perfiles_definidos" name="perfiles_definidos" required>
                    <option>Sí</option>
                    <option>No</option>
                </select>
            </div>
            <div class="forma-row">
                <label for="publicacion">Medios de publicación de vacantes:</label>
                <select id="publicacion" name="publicacion" required>
                    <option>Redes sociales</option>
                    <option>Servicio Público de Empleo</option>
                    <option>Referidos</option>
                    <option>Otras</option>
                </select>
            </div>

            <!-- Sección 4: Interés -->
            <div class="forma-row">
                <label for="aprendices">¿Vincular aprendices SENA?</label>
                <select id="aprendices" name="aprendices" required>
                    <option>Sí</option>
                    <option>No</option>
                </select>
            </div>
            <div class="forma-row">
                <label for="programa_apoyo">Participar en programa de apoyo:</label>
                <select id="programa_apoyo" name="programa_apoyo" required>
                    <option>Sí</option>
                    <option>No</option>
                </select>
            </div>
            <div class="forma-row">
                <label for="perfiles_necesarios">Perfiles requeridos actuales:</label>
                <input type="text" id="perfiles_necesarios" name="perfiles_necesarios" required>
            </div>

            <!-- Sección 5: Apoyos -->
            <div class="forma-row">
                <label for="infraestructura">Tiene infraestructura para formar:</label>
                <select id="infraestructura" name="infraestructura" required>
                    <option>Sí</option>
                    <option>No</option>
                </select>
            </div>
            <div class="forma-row">
                <label for="apoyo_seleccion">Requiere apoyo en selección:</label>
                <select id="apoyo_seleccion" name="apoyo_seleccion" required>
                    <option>Sí</option>
                    <option>No</option>
                </select>
            </div>
            <div class="forma-row">
                <label for="beneficios">Desea orientación tributaria:</label>
                <select id="beneficios" name="beneficios" required>
                    <option>Sí</option>
                    <option>No</option>
                </select>
            </div>

            <div class="buttons-container">
                <button type="submit" class="logout-btn">Enviar Diagnóstico</button>
                <a href="../empresa/empresa.menu.html" class="back-btn">Regresar</a>
            </div>
        </form>
    </div>

    <?php if ($mensaje_exito): ?>
    <style>
        .toast-success {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            font-family: sans-serif;
            z-index: 1000;
            animation: fadein 0.5s, fadeout 0.5s 3s;
        }
        @keyframes fadein {
            from {opacity: 0;}
            to {opacity: 1;}
        }
        @keyframes fadeout {
            from {opacity: 1;}
            to {opacity: 0;}
        }
    </style>
    <div class="toast-success">✅ Diagnóstico guardado correctamente</div>
    <script>
        setTimeout(() => {
            const toast = document.querySelector(".toast-success");
            if (toast) toast.remove();
        }, 4000);
    </script>
    <?php endif; ?>

    <footer>
        <a>&copy; Todos los derechos reservados al SENA</a>
    </footer>
</body>
</html>
