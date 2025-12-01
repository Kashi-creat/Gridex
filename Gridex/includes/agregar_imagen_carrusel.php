<?php
include("db.php");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url_imagen = isset($_POST['url_imagen']) ? trim($_POST['url_imagen']) : '';
    $alt_text = isset($_POST['alt_text']) ? trim($_POST['alt_text']) : '';

    if (!empty($url_imagen) && !empty($alt_text)) {
        $url = $conexion->real_escape_string($url_imagen);
        $alt = $conexion->real_escape_string($alt_text);

        $sql = "INSERT INTO carrusel (imagen, alt_text, activo) VALUES ('$url', '$alt', 1)";
        if ($conexion->query($sql)) {
            echo json_encode(["success" => true, "message" => "Imagen agregada correctamente."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error en la base de datos: " . $conexion->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Faltan datos del formulario."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}
?>
