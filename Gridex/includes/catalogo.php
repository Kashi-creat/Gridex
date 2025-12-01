<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("db.php");

$esTrabajador = isset($_SESSION['rol']) && $_SESSION['rol'] === 'trabajador';

// Condición distinta según rol
if ($esTrabajador) {
    $condicion = "activo = 1";
} else {
    $condicion = "activo = 1 AND stock > 0";
}

$busqueda = '';
if (isset($_REQUEST['buscar']) && !empty(trim($_REQUEST['buscar']))) {
    $busqueda = strtolower(trim($_REQUEST['buscar']));
    $busqueda_esc = mysqli_real_escape_string($conexion, $busqueda);
    $condicion .= " AND LOWER(nombre) LIKE '" . $busqueda_esc . "%'";
}

$categoriaFiltro = '';
if (isset($_REQUEST['categoria']) && !empty($_REQUEST['categoria'])) {
    $categoriaFiltro = mysqli_real_escape_string($conexion, $_REQUEST['categoria']);
    $condicion .= " AND categoria = '$categoriaFiltro'";
}

$sqlCategorias = "SELECT DISTINCT categoria FROM productos WHERE $condicion ORDER BY categoria ASC";
$resultCategorias = $conexion->query($sqlCategorias);

if ($resultCategorias && $resultCategorias->num_rows > 0) {
    echo '<style>
        .producto-card {
            position: relative;
            height: 16rem;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -2px rgb(0 0 0 / 0.05);
            cursor: pointer;
            border: 3px solid #FA00C9;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .producto-card:hover {
            border-color: #FF0027;
            box-shadow: 0 0 15px 3px rgba(250, 0, 201, 0.7);
        }
        .producto-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: rgba(12, 0, 39, 0.9);
            color: white;
            padding: 1rem 1.5rem;
            height: 6rem;
            backdrop-filter: blur(6px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: height 0.3s ease;
            overflow: hidden;
            z-index: 20;
        }
        .producto-card:hover .overlay {
            height: 8rem;
        }
        .precio-boton {
            display: flex;
            justify-content: space-between;
            align-items: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 30;
        }
        .producto-card:hover .precio-boton {
            opacity: 1;
            pointer-events: auto;
        }
        .titulo {
            font-weight: 700;
            font-size: 1.125rem;
            color: #FA00C9;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .descripcion {
            font-size: 0.875rem;
            color: #d1d5db;
            line-height: 1.25;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .precio {
            font-weight: 600;
        }
        .btn-agregar, .btn-editar, .btn-eliminar {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            color: white;
        }
        .btn-agregar {
            background-color: #FA00C9;
        }
        .btn-agregar:hover {
            background-color: #FF0027;
        }
        .btn-editar {
            background-color: #007bff;
        }
        .btn-editar:hover {
            background-color: #0056b3;
        }
        .btn-eliminar {
            background-color: #dc3545;
            margin-left: 0.5rem;
        }
        .btn-eliminar:hover {
            background-color: #a71d2a;
        }
        .agregar-producto {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #FA00C9;
            cursor: pointer;
            border: 3px dashed #FA00C9;
            border-radius: 1rem;
            height: 16rem;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .agregar-producto:hover {
            background-color: rgba(250,0,201,0.1);
            color: #FF0027;
        }
    </style>';

    echo '<div class="container py-4">';

    while ($cat = $resultCategorias->fetch_assoc()) {
        $categoria = $cat['categoria'];
        echo "<h2 class='text-2xl text-pink-400 font-bold mt-5 mb-3 border-b-2 border-pink-500'>" . htmlspecialchars($categoria) . "</h2>";

        if (!empty($busqueda)) {
            $likeBusqueda = $busqueda . '%';
            if ($esTrabajador) {
                $stmt = $conexion->prepare("SELECT * FROM productos WHERE activo = 1 AND categoria = ? AND LOWER(nombre) LIKE BINARY ? ORDER BY nombre ASC");
            } else {
                $stmt = $conexion->prepare("SELECT * FROM productos WHERE activo = 1 AND stock > 0 AND categoria = ? AND LOWER(nombre) LIKE BINARY ? ORDER BY nombre ASC");
            }
            $stmt->bind_param("ss", $categoria, $likeBusqueda);
        } else {
            if ($esTrabajador) {
                $stmt = $conexion->prepare("SELECT * FROM productos WHERE activo = 1 AND categoria = ? ORDER BY nombre ASC");
            } else {
                $stmt = $conexion->prepare("SELECT * FROM productos WHERE activo = 1 AND stock > 0 AND categoria = ? ORDER BY nombre ASC");
            }
            $stmt->bind_param("s", $categoria);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();

        echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">';

        if ($esTrabajador) {
            echo '
            <div class="col">
                <div class="agregar-producto" data-categoria="' . htmlspecialchars($categoria) . '" role="button" tabindex="0" data-bs-toggle="modal" data-bs-target="#modalAgregar" aria-label="Agregar nuevo producto a ' . htmlspecialchars($categoria) . '">
                    +
                </div>
            </div>';
        }

        while ($producto = $resultado->fetch_assoc()) {
            $img = !empty($producto["imagen_url"]) ? htmlspecialchars($producto["imagen_url"]) : "assets/img/default.png";

            echo '
            <div class="col">
                <div class="producto-card">
                    <img src="' . $img . '" alt="' . htmlspecialchars($producto["nombre"]) . '" class="producto-img">
                    <div class="overlay">
                        <div>
                            <h3 class="titulo">' . htmlspecialchars($producto["nombre"]) . '</h3>
                            <p class="descripcion">' . htmlspecialchars($producto["descripcion"]) . '</p>
                        </div>
                        <div class="precio-boton">';

            if ($esTrabajador) {
                echo '
                    <button class="btn-editar" data-id="' . $producto["id"] . '" data-bs-toggle="modal" data-bs-target="#modalEditarProducto">Editar</button>
                    <button class="btn-eliminar" data-id="' . $producto["id"] . '">Eliminar</button>';
            } else {
                echo '
                    <span class="precio">$' . number_format($producto["precio"], 0, ",", ".") . ' ARS</span>
                    <div class="d-flex gap-1">
                        <form action="./includes/agregar_carrito.php" method="POST">
                            <input type="hidden" name="producto_id" value="' . $producto["id"] . '">
                            <button type="submit" class="btn-agregar">Agregar</button>
                        </form>
                        <form action="./includes/comprar.php" method="POST">
                            <input type="hidden" name="producto_id" value="' . $producto["id"] . '">
                            <button type="submit" class="btn-agregar" style="background-color:#6A006C;">Comprar</button>
                        </form>
                    </div>';
            }

            echo '
                        </div>
                    </div>
                </div>
            </div>';
        }

        echo '</div>';
        $stmt->close();
    }

    echo '</div>';
} else {
    echo "<p class='text-white text-center mt-4'>No hay productos disponibles en este momento.</p>";
}

$conexion->close();
?>

<!-- Modal Agregar Producto -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background-color:#0C0027; color:white;">
      <div class="modal-header" style="border-bottom-color: #FA00C9;">
        <h5 class="modal-title" id="modalAgregarLabel">Agregar Producto</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formAgregarProducto" enctype="multipart/form-data">
            <div id="mensajeAgregar"></div>

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input type="number" name="precio" class="form-control" step="0.01" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <select name="categoria" class="form-select" required>
                <option value="App y Software">App y Software</option>
                <option value="Giftcards">Giftcards</option>
                <option value="Streaming">Streaming</option>
                <option value="Juegos">Juegos</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">URL de la imagen</label>
                <input type="url" name="imagen" class="form-control" placeholder="https://example.com/imagen.jpg" required>
            </div>
            <button type="submit" class="px-4 py-2 rounded text-white bg-[#FA00C9] hover:bg-[#c700a5] transition">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Editar Producto -->
<div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-labelledby="modalEditarProductoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background-color:#0C0027; color:white;">
      <div class="modal-header" style="border-bottom-color: #FA00C9;">
        <h5 class="modal-title" id="modalEditarProductoLabel">Editar Producto</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="modalEditarProductoBody">
        <!-- Aquí se cargará el formulario vía AJAX -->
        <div class="text-center py-5 text-pink-500">Cargando formulario...</div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  asignarEventosCatalogo();
});

function cargarCatalogo(categoria = '') {
  let url = './includes/catalogo.php';
  if (categoria) {
    url += '?categoria=' + encodeURIComponent(categoria);
  }

  fetch(url)
    .then(response => response.text())
    .then(html => {
      const contenedor = document.querySelector('.container.py-4');
      if (contenedor) {
        contenedor.outerHTML = html;
        asignarEventosCatalogo(); // Reasignar eventos a los botones
      }
    })
    .catch(error => {
      console.error('Error al cargar catálogo:', error);
    });
}

function asignarEventosCatalogo() {
  // Editar producto
  document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.addEventListener('click', function () {
      const id = this.dataset.id;
      const modalBody = document.getElementById('modalEditarProductoBody');
      modalBody.innerHTML = `
        <div class="text-center py-5 text-pink-500">
          <div class="spinner-border text-pink-500" role="status">
            <span class="visually-hidden">Cargando...</span>
          </div>
        </div>`;

      fetch('./includes/editar_producto.php?id=' + id + '&modo=modal')
        .then(response => response.text())
        .then(html => modalBody.innerHTML = html)
        .catch(() => modalBody.innerHTML = '<div class="alert alert-danger">No se pudo cargar el formulario.</div>');
    });
  });

  // Eliminar producto
  document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.addEventListener('click', function () {
      const id = this.dataset.id;
      if (confirm('¿Estás seguro de que querés eliminar este producto?')) {
        window.location.href = './includes/eliminar_producto.php?id=' + id;
      }
    });
  });

  // Agregar producto: setear categoría
  document.querySelectorAll('.agregar-producto').forEach(div => {
    div.addEventListener('click', function () {
      const categoria = this.dataset.categoria;
      const selectCategoria = document.querySelector('#modalAgregar select[name="categoria"]');
      if (selectCategoria) {
        selectCategoria.value = categoria;
      }
    });
  });
}
</script>