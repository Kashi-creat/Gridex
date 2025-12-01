<?php
require_once 'db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] ?? '') !== 'trabajador') {
    http_response_code(403);
    echo json_encode(['success' => false, 'mensaje' => 'Acceso no autorizado.']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $campos = ['nombre', 'descripcion', 'precio', 'categoria', 'imagen_url', 'stock'];
    foreach ($campos as $campo) {
        if (empty($_POST[$campo])) {
            echo json_encode(['success' => false, 'mensaje' => '⚠️ Todos los campos son obligatorios.']);
            exit;
        }
    }

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $categoria = trim($_POST['categoria']);
    $imagen_url = trim($_POST['imagen_url']);
    $stock = intval($_POST['stock']);

    $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria, imagen_url, stock)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssdssi", $nombre, $descripcion, $precio, $categoria, $imagen_url, $stock);

    if ($stmt->execute()) {
        $idInsertado = $stmt->insert_id;
        echo json_encode([
            'success' => true,
            'mensaje' => '✅ Producto agregado con éxito.',
            'producto' => [
                'id' => $idInsertado,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'categoria' => $categoria,
                'imagen_url' => $imagen_url,
                'stock' => $stock
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'mensaje' => '❌ Error: ' . htmlspecialchars($stmt->error)]);
    }

    $stmt->close();
}