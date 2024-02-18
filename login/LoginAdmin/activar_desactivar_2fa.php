<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['Rol'] !== 1) {
    header('Location: ../../index.html');
    exit();
}

$usuarioId = $_SESSION['usuario'];

if (isset($_POST['activar_2fa'])) {
    // Lógica para activar 2FA
    // Generar clave secreta, almacenarla en la base de datos, etc.
} elseif (isset($_POST['desactivar_2fa'])) {
    // Lógica para desactivar 2FA
}

header('Location: index.php'); // Redirige a la página principal del administrador
exit();
?>
