<?php
session_start();

// Verificar si hay sesión iniciada
if (!isset($_SESSION['usuario'])) {
    header("Location: login_view.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Super administrador</title>
    <link rel="stylesheet" href="../css/empresa/menu.empresa.css">
    <link rel="icon" href="../img/Logotipo_Datasena.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<!-- Fondo oscuro cuando un menú está activo -->
<div id="overlay" class="overlay" onclick="closeAllDropdowns()"></div>

<!-- BOTONES SUPERIORES CON MENÚS DESPLEGABLES -->
<div class="top-buttons">

    <div class="dropdown-container">
        <button class="top-btn" onclick="toggleDropdown('diagnosticoMenu')">Diagnóstico empresarial</button>
        <div id="diagnosticoMenu" class="submenu">
            <a href="diagnostico_empresarial.php">Realizar diagnóstico empresarial</a>
        </div>
    </div>

    <div class="dropdown-container">
        <button class="top-btn" onclick="toggleDropdown('programaMenu')">Programas de formación</button>
        <div id="programaMenu" class="submenu">
            <a href="listar_programa.php">Listar programas</a>
        </div>
    </div>
</div>

<!-- MENÚ LATERAL DE LAS TRES LÍNEAS -->
<div class="container"><----<!-- CONTENEDOR PRINCIPAL -->
    <div class="menu-wrapper">
        <div class="dropdown">
            <button onclick="toggleDropdown('menuLateral')" class="dropdown-btn">&#9776;</button>
            <div id="menuLateral" class="dropdown-content">
                <button>Configuración</button>
                <button>Ayuda</button>
                <button onclick="location.href='logout.php'">Cerrar sesión</button>
            </div>
        </div>
    </div>

    <!-- ÁREA CENTRAL -->
    <div class="center-box">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?> 👋</h2>
        <p>Somos un sistema para la gestión de las relaciones corporativas del CDITI.</p>
    </div>
</div>

<footer>
    © 2025 Todos los derechos reservados - Proyecto SENA
</footer>

<!-- SCRIPTS -->
<script>
    let currentIndex = 0;

    function showSlide(index) {
        const slider = document.getElementById('slider');
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        currentIndex = (index + totalSlides) % totalSlides;
        slider.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';
    }

    function nextSlide() { showSlide(currentIndex + 1); }
    function prevSlide() { showSlide(currentIndex - 1); }
    setInterval(nextSlide, 5000);
    showSlide(currentIndex);

    function toggleDropdown(menuId) {
        closeAllDropdowns(menuId);
        const menu = document.getElementById(menuId);
        const overlay = document.getElementById("overlay");
        if (menu && !menu.classList.contains("show")) {
            menu.classList.add("show");
            overlay.style.display = "block";
        } else {
            menu.classList.remove("show");
            overlay.style.display = "none";
        }
    }

    function closeAllDropdowns(exceptId = null) {
        const menus = document.querySelectorAll(".submenu, .dropdown-content");
        menus.forEach(menu => {
            if (menu.id !== exceptId) {
                menu.classList.remove("show");
            }
        });
        document.getElementById("overlay").style.display = "none";
    }

    window.onclick = function(event) {
        if (!event.target.matches('.top-btn') && !event.target.matches('.dropdown-btn')) {
            closeAllDropdowns();
        }
    };
</script>

</body>
</html>
