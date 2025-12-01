<head>
  <style>
    /* Estilos generales y de botones */
    .btn-pink {
      background-color: #FA00C9;
      color: white;
      border: none;
    }
    .btn-pink:hover {
      background-color: #FF0027;
      color: white;
    }
    /* Inputs y selects */
    .form-control, .form-select {
      background-color: #1a003d !important;
      border: 1px solid #6A006C;
      color: white !important;
    }
    .form-control:focus, .form-select:focus {
      border-color: #FA00C9;
      box-shadow: 0 0 8px #FA00C9;
    }
    /* Texto con brillo */
    .glow-text {
      color: #FA00C9;
      font-weight: bold;
    }
    /* Clases utilitarias */
    .hidden {
      display: none !important;
    }
    /* Placeholder y opciones select */
    #buscar-nombre::placeholder, select option {
      color: rgb(150, 0, 120);
    }
    #buscar-categorias {
      color: rgb(150, 0, 120) !important;
      max-width: 165px;
    }
    .bg-pink {
      background-color: #FA00C9 !important;
    }
    .bg-pink-dark{
      background-color: #1a003d !important;
      border: 1px solid #6A006C;
      
    }
    .dropdown-menu {
      background-color: #0C0027 !important; /* Fondo del navbar o #1a003d */
      border: 1px solid #6A006C; /* Borde de neón */
    }

    /* Define el color del hover (rosa/morado) */
    .dropdown-menu .dropdown-item:hover,
    .dropdown-menu .dropdown-item:focus {
      background-color: #6A006C !important; /* Morado de borde como color de hover */
      color: white !important; /* Mantener el texto blanco */
    }
  </style>
</head>

<?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  $usuarioLogueado = isset($_SESSION['usuario']);
  $rol = $_SESSION['rol'] ?? null;
?>

<nav class="navbar navbar-dark" style="background-color: #0C0027;">
  <div class="container-fluid">

    <!-- IZQUIERDA: Logo, texto y link -->
    <div class="d-flex align-items-center">
      <img src="assets/img/GreedX.ico" alt="GreedX_Logo" style="max-width: 50px;">
      <span class="navbar-brand ms-2">Gridex</span>
      <a class="nav-link text-white" href="./index.php">Inicio</a>
    </div>

    <!-- CENTRO: Buscador y filtro categoría -->
    <div class="position-absolute start-50 translate-middle-x w-50" id="contenedor-buscador">
      <form class="d-flex ms-auto" method="GET" action="../index.php" id="buscadorForm">
        <input class="form-control me-1" id="buscar-nombre" type="search" placeholder="Buscar productos..." aria-label="Buscar" name="buscar" value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">

        <button class="btn btn-pink" type="submit">Buscar</button>

        <select id="buscar-categorias" class="form-select ms-3" name="categoria">
          <option value="">Todas</option>
          <?php
            $categorias = $conexion->query("SELECT DISTINCT categoria FROM productos WHERE activo = 1 AND stock > 0 ORDER BY categoria ASC");
            while ($row = $categorias->fetch_assoc()) {
                $cat = htmlspecialchars($row['categoria']);
                $selected = (isset($_GET['categoria']) && $_GET['categoria'] === $row['categoria']) ? 'selected' : '';
                echo "<option value=\"$cat\" $selected>$cat</option>";
            }
          ?>
        </select>
      </form>
    </div>

    <!-- DERECHA: Contacto, usuario login o dropdown -->
    <div class="d-flex align-items-center gap-3 ms-auto">

      <!-- Botón Login visible solo si NO está logueado -->
      <?php if ($usuarioLogueado): ?>
        <button class="btn btn-dark-pink me-2 position-relative" id="btn-ver-carrito" data-bs-toggle="modal" data-bs-target="#modalCarrito">
            <i class="fas fa-shopping-cart fa-lg"><img src="assets/img/carrito-gridex.png" alt="carrito" style="heigth: 50px; width: 50px;"></i>
            <span id="carrito-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                0
                <span class="visually-hidden">Productos en el carrito</span>
            </span>
        </button>

        <div id="dropdown-usuario" class="dropdown">
            <button class="btn p-0 border-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menú usuario">
              <img src="assets/img/icon_usuario_2.png" alt="Usuario" class="rounded-circle" style="width: 40px; height: 40px;">
            </button>
            <ul class="dropdown-menu dropdown-menu-end bg-pink-dark">
              <li><a class="dropdown-item text-white" href="#">Mi perfil</a></li>
              <?php if ($rol === 'cliente'): ?>
                <li><a class="dropdown-item text-white" href="#">Mis compras</a></li>
              <?php endif; ?>
              <?php if ($rol === 'trabajador'): ?>
                <li><a class="dropdown-item text-white" href="#" data-bs-toggle="modal" data-bs-target="#modalAgregarCarrusel">Agregar imagen al carrusel</a></li>
              <?php endif; ?>
              <li><a class="dropdown-item text-white" href="#" id="cerrar-sesion">Cerrar sesión</a></li>
            </ul>
        </div>

    <?php else: ?>
        <button id="boton-login" class="btn p-0 border-0" data-bs-toggle="modal" data-bs-target="#modalUsuario" aria-label="Iniciar sesión">
            <img src="assets/img/icon_usuario_2.png" alt="Usuario" class="rounded-circle" style="width: 40px; height: 40px;">
        </button>
    <?php endif; ?>
    </div>
  </div>

  <!-- Modales (a continuación para mantener orden) -->

  <!-- Modal para agregar imagen al carrusel -->
  <div class="modal fade" id="modalAgregarCarrusel" tabindex="-1" aria-labelledby="modalAgregarCarruselLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-white" style="background-color: #1a003d; border: 1px solid #6A006C;">
        <div class="modal-header border-0">
          <h5 class="modal-title glow-text" id="modalAgregarCarruselLabel">Agregar imagen al carrusel</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formAgregarCarrusel" method="post" action="./includes/agregar_imagen_carrusel.php">
            <div class="mb-3">
              <label for="urlImagen" class="form-label">URL de la imagen</label>
              <input type="url" class="form-control" id="urlImagen" name="url_imagen" placeholder="https://ejemplo.com/imagen.jpg" required>
            </div>
            <div class="mb-3">
              <label for="altTextCarrusel" class="form-label">Texto alternativo (alt)</label>
              <input type="text" class="form-control" id="altTextCarrusel" name="alt_text" maxlength="100" placeholder="Descripción corta para la imagen" required>
            </div>
            <button type="submit" class="btn btn-pink w-100">Agregar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Usuario (login y registro) -->
  <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-white" style="background-color: #1a003d; border: 1px solid #6A006C;">
        <div class="modal-header border-0">
          <h5 class="modal-title glow-text" id="modalUsuarioLabel">Iniciar sesión</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">

          <!-- Formulario Login -->
          <form action="./includes/login.php" method="post" id="form-login" novalidate>
            <div class="mb-3">
              <label for="login-email" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" id="login-email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="login-password" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="login-password" name="password" required>
            </div>
            <button type="submit" class="btn btn-pink w-100">Ingresar</button>
            <p class="text-center mt-3">
              ¿No tenés cuenta?
              <a href="#" class="text-decoration-none" style="color: #FA00C9;" id="link-registrate">Registrate</a>
            </p>
          </form>

          <!-- Formulario Registro (oculto inicialmente) -->
          <form action="./includes/register.php" method="post" id="form-register" style="display: none;" novalidate>
            <div class="mb-3">
              <label for="register-usuario" class="form-label">Nombre de usuario</label>
              <input type="text" class="form-control" id="register-usuario" name="usuario" required>
            </div>
            <div class="mb-3">
              <label for="register-email" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" id="register-email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="register-password" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="register-password" name="contrasena" required>
            </div>
            <button type="submit" class="btn btn-pink w-100">Registrarse</button>
            <p class="text-center mt-3">
              ¿Ya tenés cuenta?
              <a href="#" class="text-decoration-none" style="color: #FA00C9;" id="link-iniciarsesion">Iniciá sesión</a>
            </p>
          </form>

        </div>
      </div>
    </div>
  </div>

</nav>

<script>
  // === Funciones para toggle login/registro ===
  function mostrarRegistro() {
    document.getElementById("form-login").style.display = "none";
    document.getElementById("form-register").style.display = "block";
    document.getElementById("modalUsuarioLabel").textContent = "Registrarse";
  }

  function mostrarLogin() {
    document.getElementById("form-register").style.display = "none";
    document.getElementById("form-login").style.display = "block";
    document.getElementById("modalUsuarioLabel").textContent = "Iniciar sesión";
  }

  // Asignar eventos a links para alternar formularios
  document.getElementById("link-registrate").addEventListener("click", e => {
    e.preventDefault();
    mostrarRegistro();
  });
  document.getElementById("link-iniciarsesion").addEventListener("click", e => {
    e.preventDefault();
    mostrarLogin();
  });

  // === Cerrar sesión ===
  function cerrarSesion() {
    fetch('./includes/logout.php')
      .then(() => window.location.href = "./index.php")
      .catch(err => alert("Error al cerrar sesión: " + err));
  }
  document.getElementById("cerrar-sesion").addEventListener("click", e => {
    e.preventDefault();
    cerrarSesion();
  });

  // === Login con fetch ===
  document.getElementById("form-login").addEventListener("submit", e => {
    e.preventDefault();

    const email = document.getElementById("login-email").value.trim();
    const password = document.getElementById("login-password").value;

    fetch('login.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: new URLSearchParams({email, password})
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // Ocultar modal
        const modalElement = document.getElementById("modalUsuario");
        bootstrap.Modal.getOrCreateInstance(modalElement).hide();

        // Mostrar dropdown usuario, ocultar botón login
        document.getElementById("boton-login").classList.add("hidden");
        document.getElementById("dropdown-usuario").classList.remove("hidden");

        // Actualizar menú según rol
        actualizarMenuSegunRol(data.rol);
      } else {
        alert(data.message);
      }
    })
    .catch(err => alert("Error en la conexión: " + err));
  });

  // === Registro con fetch ===
  document.getElementById("form-register").addEventListener("submit", e => {
    e.preventDefault();

    const usuario = document.getElementById("register-usuario").value.trim();
    const email = document.getElementById("register-email").value.trim();
    const contrasena = document.getElementById("register-password").value;

    fetch('./includes/register.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: new URLSearchParams({usuario, email, contrasena})
    })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
      if (data.success) mostrarLogin();
    })
    .catch(err => alert("Error en el registro: " + err));
  });

  // === Actualiza menú según rol ===
  function actualizarMenuSegunRol(rol) {
    const dropdownMenu = document.querySelector("#dropdown-usuario > ul.dropdown-menu");
    if (!dropdownMenu) return;

    let html = `
      <li><a class="dropdown-item text-white" href="#">Mi perfil</a></li>
      <li><a class="dropdown-item text-white" href="#">Mis compras</a></li>
      <li><a class="dropdown-item text-white" href="#" id="cerrar-sesion">Cerrar sesión</a></li>
    `;

    if (rol === "trabajador") {
      html = `
        <li><a class="dropdown-item text-white" href="#">Mi perfil</a></li>
        <li><a class="dropdown-item text-white" href="#">Mis compras</a></li>
        <li><a class="dropdown-item text-white" href="./includes/agregar_producto.php">Agregar producto</a></li>
        <li><a class="dropdown-item text-white" href="#" id="cerrar-sesion">Cerrar sesión</a></li>
      `;
    }

    dropdownMenu.innerHTML = html;

    // Reasignar evento de cerrar sesión al nuevo enlace
    document.getElementById("cerrar-sesion").addEventListener("click", e => {
      e.preventDefault();
      cerrarSesion();
    });
  }
</script>

<script>
  // === Formulario Agregar imagen al carrusel ===
  document.getElementById("formAgregarCarrusel").addEventListener("submit", e => {
    e.preventDefault();

    const urlImagen = document.getElementById("urlImagen").value.trim();
    const altText = document.getElementById("altTextCarrusel").value.trim();

    fetch("./includes/agregar_imagen_carrusel.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({ url_imagen: urlImagen, alt_text: altText })
    })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
      if (data.success) {
        // Cerrar modal
        bootstrap.Modal.getInstance(document.getElementById("modalAgregarCarrusel")).hide();
        // Limpiar formulario
        e.target.reset();
        // Actualizar carrusel sin recargar página
        actualizarCarrusel();
      }
    })
    .catch(err => alert("Error al agregar la imagen: " + err));
  });

  // === Función para actualizar carrusel dinámicamente ===
  function actualizarCarrusel() {
    fetch('./includes/obtener_carrusel.php')
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          const carousel = document.getElementById('carouselGreedX');
          if (!carousel) return;

          // Construir indicadores
          let indicadoresHTML = '';
          data.imagenes.forEach((item, index) => {
            indicadoresHTML += `<button type="button" data-bs-target="#carouselGreedX" data-bs-slide-to="${index}" ${index === 0 ? 'class="active" aria-current="true"' : ''} aria-label="Slide ${index + 1}"></button>`;
          });

          let indicadores = carousel.querySelector('.carousel-indicators');
          if (indicadores) {
            indicadores.innerHTML = indicadoresHTML;
          } else {
            const ol = document.createElement('ol');
            ol.classList.add('carousel-indicators');
            ol.innerHTML = indicadoresHTML;
            carousel.insertBefore(ol, carousel.querySelector('.carousel-inner'));
          }

          // Construir slides
          let slidesHTML = '';
          data.imagenes.forEach((item, index) => {
            slidesHTML += `
              <div class="carousel-item ${index === 0 ? 'active' : ''}">
                <img src="${item.imagen}" alt="${item.alt_text}" class="d-block w-100" style="height: 320px; object-fit: cover; border-radius: 0.5rem;">
              </div>
            `;
          });
          carousel.querySelector('.carousel-inner').innerHTML = slidesHTML;

          // Reiniciar carrusel bootstrap
          const carouselInstance = bootstrap.Carousel.getInstance(carousel);
          if (carouselInstance) carouselInstance.dispose();

          new bootstrap.Carousel(carousel, {
            interval: 3000,
            ride: 'carousel',
            pause: 'hover'
          });

        } else {
          console.error('No se pudo actualizar el carrusel:', data.message);
        }
      })
      .catch(e => console.error('Error al actualizar carrusel:', e));
  }
</script>
<script>
  (function() {
    let lastScrollTop = 0;
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;

    window.addEventListener('scroll', () => {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

      if (scrollTop > lastScrollTop && scrollTop > 50) {
        // Scroll hacia abajo y pasamos un umbral para evitar parpadeo inicial
        navbar.style.top = '-70px'; // ajusta según la altura del navbar
      } else {
        // Scroll hacia arriba o cerca del top
        navbar.style.top = '0';
      }

      lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // evitar negativos
    });
  })();
</script>