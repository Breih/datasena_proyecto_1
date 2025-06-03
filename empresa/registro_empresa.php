<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "datasenn_db");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recolección de datos
$tipo_documento = $_POST['tipo_documento'] ?? '';
$numero_documento = $_POST['nit'] ?? '';
$nickname = $_POST['nickname'] ?? '';
$numero_telefono = $_POST['telefono'] ?? '';
$correo_electronico = $_POST['correo'] ?? '';
$confirmacion_correo = $_POST['confirmar_correo'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$actividad_economica = $_POST['actividad_economica'] ?? '';
$rol_id = 2; // Fijo para empresas
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Empresa</title>
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
      window.location.href = "http://localhost/datasenn_proyecto/empresa/bus.html";
    }
  }, 5000);
}
</script>

<?php
// Validaciones
if ($correo_electronico !== $confirmacion_correo) {
    echo "<script>showToast('❌ Los correos no coinciden', false, true);</script>";
    exit;
}

if (
    empty($tipo_documento) || empty($numero_documento) || empty($nickname) ||
    empty($numero_telefono) || empty($correo_electronico) || empty($confirmacion_correo) ||
    empty($direccion) || empty($actividad_economica)
) {
    echo "<script>showToast('❌ Todos los campos son obligatorios', false, true);</script>";
    exit;
}

// Insertar en la base de datos
$sql = "INSERT INTO empresa (
            tipo_documento, numero_documento, nickname, numero_telefono, 
            correo_electronico, confirmacion_correo, direccion, actividad_economica, rol_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssssssi", $tipo_documento, $numero_documento, $nickname, $numero_telefono,
                  $correo_electronico, $confirmacion_correo, $direccion, $actividad_economica, $rol_id);

if ($stmt->execute()) {
    $ultimo_id = $conexion->insert_id;
    echo "<script>showToast('✅ Empresa registrada correctamente con ID $ultimo_id', true);</script>";

    echo "
    <div class='datos-registrados'>
      <h2>Datos Registrados</h2>
      <p><strong>ID:</strong> $ultimo_id</p>
      <p><strong>Tipo de Documento:</strong> $tipo_documento</p>
      <p><strong>Número de Documento:</strong> $numero_documento</p>
      <p><strong>Nickname:</strong> $nickname</p>
      <p><strong>Teléfono:</strong> $numero_telefono</p>
      <p><strong>Correo Electrónico:</strong> $correo_electronico</p>
      <p><strong>Dirección:</strong> $direccion</p>
      <p><strong>Actividad Económica:</strong> $actividad_economica</p>
      <p><strong>Rol ID:</strong> $rol_id</p>
    </div>";
} else {
    $error = addslashes($stmt->error);
    echo "<script>showToast('❌ Error al registrar: $error', false, true);</script>";
}

$stmt->close();
$conexion->close();
?>
</body>
</html>