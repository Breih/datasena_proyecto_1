<?php
// Establecer conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "datasenn_db");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recoger y sanitizar datos del formulario
$tipo_documento = htmlspecialchars($_POST['tipo_documento'] ?? '');
$numero_documento = htmlspecialchars($_POST['numero_documento'] ?? '');
$nombres = htmlspecialchars($_POST['nombres'] ?? '');
$apellidos = htmlspecialchars($_POST['apellidos'] ?? '');
$nickname = htmlspecialchars($_POST['nickname'] ?? '');
$correo_electronico = filter_var($_POST['correo_electronico'] ?? '', FILTER_SANITIZE_EMAIL);
$rol_id = intval($_POST['rol_id'] ?? 0); // Leer rol_id desde el formulario (oculto)

// Validar campos obligatorios
if (
    empty($tipo_documento) || empty($numero_documento) || empty($nombres) ||
    empty($apellidos) || empty($nickname) || empty($correo_electronico) || $rol_id !== 1
) {
    die("<script>showToast('❌ Todos los campos son obligatorios o el rol no es válido');</script>");
}

// Validar formato de email
if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
    die("<script>showToast('❌ Formato de correo electrónico inválido');</script>");
}

// Verificar si el correo ya existe
$stmt_check = $conexion->prepare("SELECT id FROM admin WHERE correo_electronico = ?");
$stmt_check->bind_param("s", $correo_electronico);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    die("<script>showToast('❌ Este correo electrónico ya está registrado');</script>");
}
$stmt_check->close();

// Consulta SQL para insertar
$sql = "INSERT INTO admin (tipo_documento, numero_documento, nombres, apellidos, nickname, correo_electronico, rol_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssssi", $tipo_documento, $numero_documento, $nombres, $apellidos, $nickname, $correo_electronico, $rol_id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Administrador</title>
    <style>
        .toast {
            visibility: hidden;
            min-width: 250px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            border-radius: 5px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            right: 30px;
            bottom: 30px;
            font-size: 17px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        .toast.error {
            background-color: #f44336;
        }

        .toast.show {
            visibility: visible;
            animation: fadein 0.5s, fadeout 0.5s 3s;
        }

        @keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }

        @keyframes fadeout {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }

        .datos-registrados {
            margin: 30px auto;
            max-width: 500px;
            font-family: Arial, sans-serif;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .datos-registrados h2 {
            text-align: center;
            color: #333;
        }

        .datos-registrados p {
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div id="toast" class="toast"></div>

<script>
function showToast(mensaje, redirigir = false, esError = false) {
    const toast = document.getElementById("toast");
    toast.textContent = mensaje;
    toast.className = "toast show" + (esError ? " error" : "");

    setTimeout(() => {
        toast.className = toast.className.replace("show", "");
        if (redirigir) {
            window.location.href = "/datasenn_proyecto/admin/menu.html";    
        }
    }, 5000); // espera 5 segundos antes de redirigir
}
</script>

<?php
if ($stmt->execute()) {
    $ultimo_id = $conexion->insert_id;
    echo "<script>showToast('✅ Administrador registrado con ID $ultimo_id', true);</script>";


    // Mostrar datos registrados
    echo "
    <div class='datos-registrados'>
        <h2>Datos Registrados</h2>
        <p><strong>ID:</strong> $ultimo_id</p>
        <p><strong>Tipo de Documento:</strong> $tipo_documento</p>
        <p><strong>Número de Documento:</strong> $numero_documento</p>
        <p><strong>Nombres:</strong> $nombres</p>
        <p><strong>Apellidos:</strong> $apellidos</p>
        <p><strong>Nickname:</strong> $nickname</p>
        <p><strong>Correo Electrónico:</strong> $correo_electronico</p>
        <p><strong>Rol ID:</strong> $rol_id</p>
    </div>";
} else {
    $error = addslashes($stmt->error);
    echo "<script>showToast('❌ Error al registrar: $error', false, true);</script>";
    error_log("Error al registrar admin: " . $stmt->error);
}

$stmt->close();
$conexion->close();
?>
</body>
</html>
