<?php
session_start();
include("db.php");

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// 1. SIMULACIÓN DEL PROCESO DE COMPRA DEL CARRITO
// **********************************************
// En un sistema real, aquí se procesarían todos los ítems del carrito:
// A) Validar stock de todos los productos en la tabla 'carrito'.
// B) Procesar el pago de todos.
// C) Descontar el stock y generar pedidos/códigos (transacción segura).
// **********************************************

// 2. Limpiar el carrito después de la compra (simulada)
$sql_limpiar = "DELETE FROM carrito WHERE usuario_id = ?";
$stmt = $conexion->prepare($sql_limpiar);
$stmt->bind_param("i", $usuario_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Carrito limpiado. Compra finalizada.']);
} else {
    // En caso de error, no debes limpiar el carrito en un sistema real.
    echo json_encode(['success' => false, 'message' => 'Error al limpiar el carrito (simulación fallida).']);
}
$stmt->close();
?>