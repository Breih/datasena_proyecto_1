<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=datasenn_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$usuario = $_POST['usuario'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

// Usa el nombre correcto de la columna: ¿correo? ¿nombre_usuario? etc.
$sql = "SELECT * FROM usuarios WHERE correo = :usuario"; 
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':usuario', $usuario);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si usas contraseñas encriptadas:
    if (password_verify($contrasena, $fila['contrasena'])) {
        $_SESSION['usuario'] = $usuario;
        header("Location: empresa.menu.php");
        exit();
    } else {
        echo "Contraseña incorrecta.";
    }

    // Si NO usas contraseñas encriptadas, usa esto en su lugar:
    // if ($contrasena == $fila['contrasena']) { ... }
} else {
    echo "Usuario no encontrado.";
}
?>
