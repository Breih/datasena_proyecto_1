<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "datasenn_db";

$conn = new mysqli($host, $usuario, $contrasena, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    echo '
    <style>
        .toast-error {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #f44336;
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
    <div class="toast-error">❌ Error de conexión: ' . $conn->connect_error . '</div>
    <script>
        setTimeout(() => {
            const toast = document.querySelector(".toast-error");
            if (toast) toast.remove();
        }, 4000);
    </script>';
    exit;
}
?>