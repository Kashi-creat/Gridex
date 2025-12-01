<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');

$response = [
    'logged_in' => false,
    'has_items' => false,
    'html_content' => '<div class="alert alert-warning text-center">Debes iniciar sesión para ver tu carrito.</div>',
    'total' => 0.00
];

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode($response);
    exit;
}

$response['logged_in'] = true;
$usuario_id = $_SESSION['usuario_id'];
$total_carrito = 0;
$items_html = '';

$sql = "SELECT c.producto_id, p.nombre, p.precio, p.imagen_url, c.cantidad
        FROM carrito c
        JOIN productos p ON c.producto_id = p.id
        WHERE c.usuario_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $response['has_items'] = true;
    $items_html .= '<ul class="list-group list-group-flush bg-transparent">';
    
    while ($item = $resultado->fetch_assoc()) {
        $subtotal = $item['precio'] * $item['cantidad'];
        $total_carrito += $subtotal;
        
        $items_html .= '<li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-secondary">';
        $items_html .= '  <div class="d-flex align-items-center">';
        $items_html .= '    <img src="' . htmlspecialchars($item['imagen_url']) . '" alt="' . htmlspecialchars($item['nombre']) . '" style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px; border-radius: 5px;">';
        $items_html .= '    <div>';
        $items_html .= '      <h6 class="mb-0 text-white">' . htmlspecialchars($item['nombre']) . '</h6>';
        $items_html .= '      <small class="text-white">Cant: ' . $item['cantidad'] . ' x $' . number_format($item['precio'], 2) . '</small>';
        $items_html .= '    </div>';
        $items_html .= '  </div>';
        $items_html .= '  <span class="badge bg-pink text-white fs-6">$' . number_format($subtotal, 2) . '</span>';
        $items_html .= '</li>';
    }
    $items_html .= '</ul>';
    
    $items_html .= '<div class="text-end mt-3 me-2">';
    $items_html .= '  <h4 class="glow-text">Total: $' . number_format($total_carrito, 2) . '</h4>';
    $items_html .= '</div>';
    $response['total'] = $total_carrito;

} else {
    $response['html_content'] = '<div class="alert alert-info text-center text-dark">Tu carrito está vacío. ¡Añade algunos productos!</div>';
}

if ($response['has_items']) {
    $response['html_content'] = $items_html;
}

echo json_encode($response);
?>