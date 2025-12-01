<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Gridex</title>
  <!-- Configuración personalizada para Tailwind (antes del script) -->
  <script>
    tailwind.config = {
      safelist: [
        'group-hover:opacity-100',
        'group-hover:pointer-events-auto',
        'opacity-0',
        'transition-opacity',
        'duration-300'
      ]
    };
  </script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/a2e0d5b6c5.js" crossorigin="anonymous"></script>

  <style>
    body {
      margin: 0;
      background: radial-gradient(circle at top left, #1A0035, #0C0027);

      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card-custom {
      background-color: #1a003d;
      border: 1px solid #6A006C;
      color: white;
      border-radius: 1rem;
      box-shadow: 0 0 15px rgba(250, 0, 201, 0.2);
    }

    .btn-pink {
      background-color: #FA00C9;
      color: white;
      border: none;
    }

    .btn-pink:hover {
      background-color: #FF0027;
      color: white;
    }

    .form-control {
      background-color: #1a003d;
      border: 1px solid #6A006C;
      color: white;
    }

    .form-control:focus {
      border-color: #FA00C9;
      box-shadow: 0 0 8px #FA00C9;
    }

    .glow-text {
      color: #FA00C9;
      font-weight: bold;
    }
  </style>
</head>
<body class="text-white">

<?php include("includes/db.php");?>

<?php include ('includes/navbar.php');?>

<?php include ('includes/chatbot.php');?>

<?php include ('includes/carrusel.php');?>

<div id="catalogoContainer">
  <?php include("includes/catalogo.php"); ?>
</div>

<div class="modal fade" id="modalCarrito" tabindex="-1" aria-labelledby="modalCarritoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-white" style="background-color: #1a003d; border: 1px solid #6A006C;">
      <div class="modal-header border-0">
        <h5 class="modal-title glow-text" id="modalCarritoLabel"><i class="fas fa-shopping-cart me-2"></i>Tu Carrito</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="carrito-body-content">
        <div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x glow-text"></i> <p class="mt-2">Cargando...</p></div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-pink" data-bs-dismiss="modal">Seguir Comprando</button>
        <button type="button" class="btn btn-pink" id="btn-finalizar-compra" style="display: none;">Comprar Todo</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script principal -->
<script>
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

        fetch('includes/editar_producto.php?id=' + id + '&modo=modal')
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
          window.location.href = 'includes/eliminar_producto.php?id=' + id;
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

  document.addEventListener('DOMContentLoaded', () => {
    asignarEventosCatalogo(); // Para botones cargados inicialmente
  });

  document.getElementById('buscadorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);

    fetch('includes/catalogo.php?' + params.toString())
      .then(response => response.text())
      .then(data => {
        document.getElementById('catalogoContainer').innerHTML = data;
        asignarEventosCatalogo(); // Reasigna eventos a nuevos botones
      })
      .catch(error => console.error('Error en la búsqueda:', error));
  });

  document.getElementById('buscar-categorias').addEventListener('change', function() {
    document.getElementById('buscadorForm').dispatchEvent(new Event('submit'));
  });

// Función para cargar el contenido del carrito en la modal
function cargarCarrito() {
  const modalBody = document.getElementById('carrito-body-content');
  const btnComprar = document.getElementById('btn-finalizar-compra');
  
  // Mostrar Spinner de carga
  modalBody.innerHTML = `<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x glow-text"></i> <p class="mt-2">Cargando...</p></div>`;
  btnComprar.style.display = 'none'; // Ocultar el botón mientras carga

  fetch('./includes/obtener_carrito.php')
    .then(response => response.json()) // Esperamos un JSON ahora
    .then(data => {
      // 1. Mostrar el contenido (HTML)
      modalBody.innerHTML = data.html_content;
      
      // 2. Controlar la visibilidad del botón "Comprar Todo"
      if (data.logged_in && data.has_items) {
        btnComprar.style.display = 'block';
      } else {
        btnComprar.style.display = 'none';
      }
      
      // 3. Actualizar el contador
      actualizarContadorCarrito(data.has_items);
    })
    .catch(error => {
      console.error('Error al cargar el carrito:', error);
      modalBody.innerHTML = `<div class="alert alert-danger">Error al cargar el carrito: No se pudo contactar al servidor.</div>`;
      btnComprar.style.display = 'none';
    });
}

// Función de contador (actualizada)
function actualizarContadorCarrito(hasItems) {
    const badge = document.getElementById('carrito-count');
    if (hasItems) {
        badge.style.display = 'block';
        badge.textContent = '1+'; // Sigue siendo un placeholder simple
    } else {
        badge.style.display = 'none';
        badge.textContent = '0';
    }
}

// Función para la compra (simulada)
function procesarCompraCarrito() {
  
  // Ocultar modal del carrito antes de procesar
  const modalCarrito = bootstrap.Modal.getInstance(document.getElementById('modalCarrito'));
  if(modalCarrito) modalCarrito.hide();

  fetch('includes/procesar_compra_carrito.php', { method: 'POST' })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Muestra el mensaje de éxito (SweetAlert2)
        Swal.fire({
            title: '¡Gracias por tu compra! 🎉',
            text: 'Tu pedido ha sido procesado con éxito y tu carrito ha sido vaciado.',
            icon: 'success',
            confirmButtonText: 'Genial',
            confirmButtonColor: '#FA00C9'
        }).then(() => {
           // *** ESTA ES LA LÍNEA CLAVE ***
           // En lugar de recargar la página, solo volvemos a cargar el contenido del carrito.
           // Como el backend ya lo vació, aparecerá vacío la próxima vez que lo abras.
           cargarCarrito(); 
        });
      } else {
         Swal.fire({
            title: 'Error de compra',
            text: data.message || 'Hubo un error al procesar tu compra.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
      }
    })
    .catch(error => {
      console.error('Error AJAX:', error);
      Swal.fire({
          title: 'Error de conexión',
          text: 'No se pudo contactar al servidor.',
          icon: 'error',
          confirmButtonText: 'Aceptar'
      });
    });
}

// ----------------------------------------------------
// EVENTOS
// ----------------------------------------------------

document.addEventListener('DOMContentLoaded', () => {
    // 1. Evento para cargar el carrito cuando se abre la modal
    const modalCarritoEl = document.getElementById('modalCarrito');
    if (modalCarritoEl) {
        modalCarritoEl.addEventListener('show.bs.modal', cargarCarrito);
    }
    
    // 2. Evento para finalizar la compra desde el botón del carrito
    document.getElementById('btn-finalizar-compra').addEventListener('click', procesarCompraCarrito);

    // 3. Modificación del comportamiento de "Comprar" directo (comprar.php) 
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('directo') === '1' && urlParams.get('mensaje') === 'gracias') {
        // Asumimos que la compra directa se completó en checkout.php
        Swal.fire({
            title: '¡Gracias por su compra! 🎉',
            text: 'Tu producto se ha reservado.',
            icon: 'success',
            confirmButtonText: 'Genial',
            confirmButtonColor: '#FA00C9'
        }).then(() => {
            // Limpiar la URL después de mostrar el mensaje
            window.history.replaceState(null, null, window.location.pathname);
        });
    }
    
});

</script>

<?php
  include("includes/footer.php");
?>
</body>
</html>