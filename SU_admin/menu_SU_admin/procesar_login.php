<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=admin", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$usuario = $_POST['usuario'] ?? '';
$contrasena = $_POST['contraseña'] ?? '';

$sql = "SELECT * FROM admin WHERE usuario = :usuario";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':usuario', $usuario);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($contrasena, $admin['contrasena'])) {
        $_SESSION['usuario'] = $admin['usuario']; // o $admin['nombre'] si prefieres
        header("Location: super.menu.php");
        exit();
    } else {
        echo "<script>alert('Contraseña incorrecta'); window.location.href='super_login.php';</script>";
    }
} else {
    echo "<script>alert('Usuario no encontrado'); window.location.href='super_login.php';</script>";
}
?>
