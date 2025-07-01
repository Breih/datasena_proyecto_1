<?php
$errores = [];
$datos = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Captura de datos
    $campos = [
        'tipo_documento', 'numero_identidad', 'nickname',
        'telefono', 'correo', 'direccion',
        'actividad_economica', 'estado'
    ];

    foreach ($campos as $campo) {
        $datos[$campo] = trim($_POST[$campo] ?? '');
        if (empty($datos[$campo])) {
            $errores[$campo] = "Este campo es obligatorio.";
        }
    }

    if (!empty($datos['correo']) && !filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
        $errores['correo'] = "Correo electrónico no válido.";
    }

    if (!empty($datos['telefono']) && !preg_match('/^\d{10}$/', $datos['telefono'])) {
        $errores['telefono'] = "Debe tener exactamente 10 dígitos.";
    }

    if (empty($errores)) {
        try {
            $conexion = new PDO("mysql:host=localhost;dbname=datasenn_db;charset=utf8", "root", "");
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO empresas (
                        tipo_documento, numero_identidad, nickname, telefono,
                        correo, direccion, actividad_economica, estado
                    ) VALUES (
                        :tipo_documento, :numero_identidad, :nickname, :telefono,
                        :correo, :direccion, :actividad_economica, :estado
                    )";

            $stmt = $conexion->prepare($sql);
            foreach ($datos as $campo => $valor) {
                $stmt->bindValue(":$campo", $valor);
            }
            $stmt->execute();
            $exito = "Empresa registrada exitosamente.";
            $datos = [];

        } catch (PDOException $e) {
            $errores['general'] = "Error en base de datos: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Registro de Empresa</title>
    <link rel="stylesheet" href="../../../css/SU_admin/menu_SU_admin/empresa_registro.css" />
</head>
<body>
    <h2>DATASENA</h2>
    <h1>DATASENA</h1>
    <img src="../../../img/logo-sena.png" alt="Logo" class="img" />

<div class="forma-container">
    <h3>Registro de Empresa</h3>

    <?php if (!empty($errores['general'])): ?>
        <div class="mensaje-error"><?= htmlspecialchars($errores['general']) ?></div>
    <?php endif; ?>
    <?php if (!empty($exito)): ?>
        <div class="mensaje-exito"><?= htmlspecialchars($exito) ?></div>
    <?php endif; ?>

<form action="" method="POST">
  <div class="forma-grid">
    <div>
<div class="forma-row">
  <label for="tipo_documento">Tipo de Documento de la Empresa:</label>
  <select id="tipo_documento" name="tipo_documento" required class="md-input">
    <option value="">Seleccione una opción</option>
    <option value="NIT" <?= ($datos['tipo_documento'] ?? '') === 'NIT' ? 'selected' : '' ?>>NIT</option>
    <option value="Registro Mercantil" <?= ($datos['tipo_documento'] ?? '') === 'Registro Mercantil' ? 'selected' : '' ?>>Registro Mercantil</option>
    <option value="Registro Cámara de Comercio Extranjera" <?= ($datos['tipo_documento'] ?? '') === 'Registro Cámara de Comercio Extranjera' ? 'selected' : '' ?>>Registro Cámara de Comercio Extranjera</option>
    <option value="Pasaporte Empresarial" <?= ($datos['tipo_documento'] ?? '') === 'Pasaporte Empresarial' ? 'selected' : '' ?>>Pasaporte Empresarial</option>
    <option value="RUT" <?= ($datos['tipo_documento'] ?? '') === 'RUT' ? 'selected' : '' ?>>RUT</option>
    <option value="Licencia Municipal" <?= ($datos['tipo_documento'] ?? '') === 'Licencia Municipal' ? 'selected' : '' ?>>Licencia Municipal</option>
  </select>
  <?php if (!empty($errores['tipo_documento'])): ?>
    <div class="mensaje-error"><?= htmlspecialchars($errores['tipo_documento']) ?></div>
  <?php endif; ?>
</div>


      <div class="forma-row">
        <label for="numero_identidad">Número de documento:</label>
        <input type="text" id="numero_identidad" name="numero_identidad" class="md-input"
               pattern="\d{8,12}" title="Debe tener entre 8 y 12 dígitos numéricos"
               value="<?= htmlspecialchars($datos['numero_identidad'] ?? '') ?>" required>
      </div>

      <div class="forma-row">
        <label for="nickname">Nombre de la empresa:</label>
        <input type="text" id="nickname" name="nickname" class="md-input"
               pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ0-9 ]+" title="Solo letras, números y espacios"
               value="<?= htmlspecialchars($datos['nickname'] ?? '') ?>" required>
      </div>

      <div class="forma-row">
        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" class="md-input"
               pattern="\d{10}" title="Debe tener exactamente 10 dígitos"
               value="<?= htmlspecialchars($datos['telefono'] ?? '') ?>" required>
      </div>
    </div>

    <div>
      <div class="forma-row">
        <label for="correo">Correo electrónico:</label>
        <input type="email" id="correo" name="correo" class="md-input"
               value="<?= htmlspecialchars($datos['correo'] ?? '') ?>" required>
      </div>

      <div class="forma-row">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" class="md-input"
               value="<?= htmlspecialchars($datos['direccion'] ?? '') ?>" required>
      </div>

      <div class="forma-row">
        <label for="actividad_economica">Actividad Económica:</label>
        <input type="text" id="actividad_economica" name="actividad_economica" class="md-input"
               pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ0-9 ,.]+" title="Solo letras, números, comas y puntos"
               value="<?= htmlspecialchars($datos['actividad_economica'] ?? '') ?>" required>
      </div>

      <div class="forma-row">
        <label for="estado">Estado:</label>
        <select id="estado" name="estado" required>
          <option value="">Seleccione</option>
          <option value="1" <?= ($datos['estado'] ?? '') == '1' ? 'selected' : '' ?>>Activo</option>
          <option value="0" <?= ($datos['estado'] ?? '') == '0' ? 'selected' : '' ?>>Inactivo</option>
        </select>
      </div>
    </div>
  </div>

  <div class="logout-buttons-container">
    <button type="submit" class="logout-btn">Crear</button>
    <button type="button" class="logout-btn" onclick="window.location.href='../super_menu.html'">Regresar</button>
  </div>
</form>
</div>


    <footer>&copy; Todos los derechos reservados al SENA</footer>
</body>
</html>
