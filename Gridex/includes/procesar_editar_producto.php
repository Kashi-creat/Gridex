<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'trabajador') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $categoria = trim($_POST['categoria']);
    $imagen_url = trim($_POST['imagen_url']);
    $stock = intval($_POST['stock']);

    $sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, categoria=?, imagen_url=?, stock=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssdssii", $nombre, $descripcion, $precio, $categoria, $imagen_url, $stock, $id);

    if ($stmt->execute()) {
        header("Location: ../index.php?mensaje=editado");
    } else {
        echo "Error al actualizar: " . htmlspecialchars($stmt->error);
    }
}
?>