<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'trabajador') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: ../index.php");
    exit();
}

$mensaje = "";

// Obtener datos del producto
$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$producto = $resultado->fetch_assoc();

if (!$producto) {
    $mensaje = "<div class='alert alert-danger'>Producto no encontrado.</div>";
}

// Si está en modo modal, solo renderiza el formulario
if (isset($_GET['modo']) && $_GET['modo'] === 'modal') {
    ob_start();
    ?>
    <form id="formEditarProducto" method="POST" action="./includes/procesar_editar_producto.php">
        <input type="hidden" name="id" value="<?= $producto['id'] ?>">

        <div class="mb-3">
            <label class="form-label glow-text">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label glow-text">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label glow-text">Precio</label>
            <input type="number" name="precio" class="form-control" step="0.01" min="0" value="<?= $producto['precio'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label glow-text" for="categoria">Categoría</label>
            <select class="form-control" id="categoria" name="categoria" required>
                <option value="">Seleccionar una categoría</option>
                <option value="Juegos" <?= $producto['categoria'] === 'Juegos' ? 'selected' : '' ?>>🎮 Juegos</option>
                <option value="Streaming" <?= $producto['categoria'] === 'Streaming' ? 'selected' : '' ?>>🎬 Streaming</option>
                <option value="Giftcards" <?= $producto['categoria'] === 'Giftcards' ? 'selected' : '' ?>>🎁 Giftcards</option>
                <option value="Apps y software" <?= $producto['categoria'] === 'Apps y software' ? 'selected' : '' ?>>💻 Apps y software</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label glow-text">Imagen URL</label>
            <input type="url" name="imagen_url" class="form-control" value="<?= htmlspecialchars($producto['imagen_url']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label glow-text">Stock</label>
            <input type="number" name="stock" class="form-control" min="0" value="<?= $producto['stock'] ?>" required>
        </div>
        <button type="submit" class="btn btn-pink w-100">Actualizar</button>
    </form>
    <?php
    echo ob_get_clean();
    exit();
}
?>