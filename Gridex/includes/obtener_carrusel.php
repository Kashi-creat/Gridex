<?php
include("db.php");
header('Content-Type: application/json');

$sql = "SELECT imagen, alt_text FROM carrusel WHERE activo = 1 ORDER BY id DESC";
$result = $conexion->query($sql);

$imagenes = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $imagenes[] = [
            'imagen' => $row['imagen'],
            'alt_text' => $row['alt_text']
        ];
    }
    echo json_encode(['success' => true, 'imagenes' => $imagenes]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al obtener imágenes']);
}
?>