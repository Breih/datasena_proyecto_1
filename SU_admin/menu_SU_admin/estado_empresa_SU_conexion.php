<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "123456", "datasenn_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Inicializar datos de la empresa
$empresa = [
    'tipo_documento' => '',
    'nit' => '',
    'nickname' => '',
    'telefono' => '',
    'correo' => '',
    'direccion' => '',
    'rol' => '',
    'actividad_economica' => '',
    'fecha_registro' => '',
    'estado' => ''
];

$mensaje = "";
$empresa_encontrada = false;

// Actualizar estado de empresa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_estado'])) {
    $nit = $_POST['nit'];
    $nuevo_estado = $_POST['nuevo_estado'];

    if (!empty($nit) && !empty($nuevo_estado)) {
        $stmt = $conexion->prepare("UPDATE empresas SET estado = ? WHERE nit = ?");
        $stmt->bind_param("ss", $nuevo_estado, $nit);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $mensaje = "Estado de la empresa actualizado correctamente.";
                $mensaje_tipo = "exito";
            } else {
                $mensaje = "No se encontró la empresa o no se realizaron cambios.";
                $mensaje_tipo = "error";
            }
        } else {
            $mensaje = "Error al actualizar el estado de la empresa.";
            $mensaje_tipo = "error";
        }
        $stmt->close();
    }
}

// Buscar empresa por NIT
if (isset($_GET['nit']) || isset($_POST['buscar_empresa'])) {
    $nit = $_GET['nit'] ?? $_POST['nit'] ?? '';

    if (!empty($nit)) {
        $stmt = $conexion->prepare("SELECT tipo_documento, nit, nickname, telefono, correo, direccion, rol, actividad_economica, fecha_registro, estado FROM empresas WHERE nit = ?");
        $stmt->bind_param("s", $nit);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $empresa = $resultado->fetch_assoc();
            $empresa_encontrada = true;
        } else {
            $mensaje = "No se encontró ninguna empresa con ese NIT.";
            $mensaje_tipo = "error";
        }
        $stmt->close();
    }
}

$conexion->close();
?>
