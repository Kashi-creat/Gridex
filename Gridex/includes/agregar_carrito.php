<?php
session_start();
include("db.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php"); // O login
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$producto_id = $_POST['producto_id'];

// Insertar o actualizar el carrito
$sql = "INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, 1)
        ON DUPLICATE KEY UPDATE cantidad = cantidad + 1";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $usuario_id, $producto_id);
$stmt->execute();
$stmt->close();

header("Location: ../index.php"); // o volver al catálogo
exit();
