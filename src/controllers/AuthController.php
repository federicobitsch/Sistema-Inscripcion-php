<?php
session_start();
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../models/User.php';

$db = new Database();
$conn = $db->connect();
$user = new User($conn);

if (isset($_POST['register'])) {
    $data = [
        'nombre' => $_POST['nombre'],
        'email' => $_POST['email'],
        'password' => $_POST['password']
    ];
    $user->create($data);
    echo " Usuario registrado. <a href='../../public/login.php'>Iniciar sesión</a>";
}

if (isset($_POST['login'])) {
    $data = $user->findByEmail($_POST['email']);
    if ($data && password_verify($_POST['password'], $data['password'])) {
        $_SESSION['user_id'] = $data['id'];
        header("Location: ../../public/inscripcion.php");
        exit;
    } else {
        echo "❌ Datos incorrectos.";
    }
}
?>
