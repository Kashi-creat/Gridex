<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'db.php';

header('Content-Type: application/json'); // <- Para que el JS entienda el JSON

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $usuario = trim($_POST["usuario"]);
    $contrasena = $_POST["contrasena"];

    if (empty($email) || empty($usuario) || empty($contrasena)) {
        $response['message'] = "Por favor, completá todos los campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Correo inválido.";
    } else {
        $query_check = $conexion->prepare("SELECT id FROM usuarios WHERE email = ? OR usuario = ?");
        $query_check->bind_param("ss", $email, $usuario);
        $query_check->execute();
        $resultado = $query_check->get_result();

        if ($resultado->num_rows > 0) {
            $response['message'] = "El correo o usuario ya están en uso.";
        } else {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("INSERT INTO usuarios (email, usuario, contrasena, rol) VALUES (?, ?, ?, 'usuario')");
            $stmt->bind_param("sss", $email, $usuario, $hash);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Registro exitoso. ¡Podés iniciar sesión!";
            } else {
                $response['message'] = "Error al registrar: " . $stmt->error;
            }
            $stmt->close();
        }
        $query_check->close();
    }
}

echo json_encode($response);
?>