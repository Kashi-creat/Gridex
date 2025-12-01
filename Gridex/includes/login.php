<?php
header('Content-Type: application/json');
include("db.php");
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Campos vacíos']);
    exit;
}
// Verificá el usuario en la base de datos
$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();
if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
    if (password_verify($password, $usuario['contrasena'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario'] = $usuario['usuario'];
        $_SESSION['rol'] = $usuario['rol'];
        echo json_encode(['success' => true, 'rol' => $usuario['rol']]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    exit;
}