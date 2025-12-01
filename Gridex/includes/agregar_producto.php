<!-- Modal para agregar producto -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title glow-text" id="modalAgregarLabel">Agregar Nuevo Producto</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formAgregarProducto">
          <div id="mensajeAgregar"></div>
          <div class="mb-3">
            <label class="form-label glow-text" for="nombre">Nombre</label>
            <input type="text" class="form-control" name="nombre" required />
          </div>
          <div class="mb-3">
            <label class="form-label glow-text" for="descripcion">Descripción</label>
            <textarea class="form-control" name="descripcion" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label glow-text" for="precio">Precio</label>
            <input type="number" step="0.01" min="0" class="form-control" name="precio" required />
          </div>
          <div class="mb-3">
            <label class="form-label glow-text" for="categoria">Categoría</label>
            <select class="form-control" name="categoria" required>
              <option value="">Seleccionar una categoría</option>
              <option value="Juegos">🎮 Juegos</option>
              <option value="Streaming">🎬 Streaming</option>
              <option value="Giftcards">🎁 Giftcards</option>
              <option value="Apps y software">💻 Apps y software</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label glow-text" for="imagen_url">URL de la Imagen</label>
            <input type="url" class="form-control" name="imagen_url" required />
          </div>
          <div class="mb-3">
            <label class="form-label glow-text" for="stock">Stock</label>
            <input type="number" min="0" class="form-control" name="stock" required />
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-pink">Agregar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>