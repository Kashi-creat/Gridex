<?php
session_start();
include("db.php");

$producto_id = $_POST['producto_id'] ?? null;

if (!$producto_id) {
    // Si falta el ID, redirige al index
    header("Location: ../index.php");
    exit();
}

// 2. SIMULACIÓN DE PROCESO DE COMPRA DIRECTA
// **********************************************
// En un sistema real, aquí iría la lógica para:
// A) Verificar stock del $producto_id.
// B) Procesar el pago.
// C) Descontar el stock (UPDATE productos SET stock = stock - 1 WHERE id = $producto_id).
// D) Generar el pedido y el código/licencia asociado.
// **********************************************

// 3. Redirección con mensaje de éxito
header("Location: ../index.php?directo=1&mensaje=gracias");