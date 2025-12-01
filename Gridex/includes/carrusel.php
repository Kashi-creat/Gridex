<head>
    <style>
        @media (max-width: 768px) {
            /* Contenedor del Carrusel (opcionalmente) */
            #carouselGreedX {
                /* Define una altura máxima para el carrusel completo en móviles */
                max-height: 250px; /* Puedes ajustar este valor */
                overflow: hidden; /* Oculta cualquier parte que se desborde */
            }

            /* Imágenes dentro del Carrusel */
            #carouselGreedX .carousel-item img {
                /* Asegura que la imagen sea un bloque y ocupe el 100% del ancho de su contenedor */
                display: block;
                width: 100%;
                /* Establece la altura máxima de la imagen al 100% de la altura de su contenedor */
                height: 100%;
                /* **LA CLAVE:** Cubre el área manteniendo la proporción, recortando los bordes si es necesario */
                object-fit: cover;
            }

            /* Asegura que los items del carrusel también respeten la altura */
            #carouselGreedX .carousel-inner,
            #carouselGreedX .carousel-item {
                height: 100%;
            }
        }
        .carousel-indicators {
            position: static !important; /* Quita la posición absoluta */
            margin-top: 1rem; /* Separación arriba para que no queden pegados */
            display: flex !important;
            gap: 0.5rem;
            justify-content: center; /* Centra los indicadores */
            padding-left: 0;
            margin-bottom: 1rem;
        }

        .carousel-indicators button {
            width: 16px !important;
            height: 16px !important;
            border-radius: 50% !important;
            background-color: transparent !important;
            border: 2px solid #FA00C9 !important;
            opacity: 0.6 !important;
            padding: 0 !important;
            margin: 0 !important;
            box-sizing: content-box !important;
            transition: background-color 0.3s ease, opacity 0.3s ease;
        }

        .carousel-indicators button::before {
            display: none !important;
        }

        .carousel-indicators .active {
            background-color: #FA00C9 !important;
            opacity: 1 !important;
            box-shadow: 0 0 8px #FA00C9 !important;
            border-color: #FA00C9 !important;
        }

        .carousel-indicators button:hover {
            background-color: #FA00C9 !important;
            opacity: 0.9 !important;
            cursor: pointer;
        }
    </style>
</head>

<?php
  $indicators = '';
  $carouselItems = '';

  $sql = "SELECT imagen, alt_text FROM carrusel WHERE activo = 1 ORDER BY id ASC";
  $result = $conexion->query($sql);

  $contador = 0;

  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $activeClass = ($contador === 0) ? "active" : "";
      $ariaCurrent = ($contador === 0) ? "aria-current='true'" : "";
      $carouselItems .= '
        <div class="carousel-item ' . $activeClass . '">
          <img src="' . htmlspecialchars($row['imagen']) . '" class="d-block w-100" alt="' . htmlspecialchars($row['alt_text']) . '" style="height: 320px; object-fit: cover; border-radius: 0.5rem;">
        </div>
      ';
      $indicators .= '
        <button type="button" data-bs-target="#carouselGreedX" data-bs-slide-to="' . $contador . '" class="' . $activeClass . '" ' . $ariaCurrent . ' aria-label="Slide ' . ($contador + 1) . '"></button>
      ';
      $contador++;
    }
  } else {
    $carouselItems = '
      <div class="carousel-item active">
        <img src="assets/img/default.jpg" class="d-block w-100" alt="Imagen por defecto" style="height: 320px; object-fit: cover; border-radius: 0.5rem;">
      </div>
    ';
    $indicators = '
      <button type="button" data-bs-target="#carouselGreedX" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    ';
  }
?>

<div id="carouselGreedX" class="carousel slide mt-16 mb-4 rounded-lg shadow-lg" data-bs-ride="carousel" data-bs-pause="hover" data-bs-interval="3000" style="border-bottom: 4px solid #FA00C9;">
  
  <!-- Imágenes -->
  <div id="carouselInner" class="carousel-inner rounded-lg">
    <?php echo $carouselItems; ?>
  </div>

  <!-- Indicadores -->
  <ol class="carousel-indicators" id="carouselIndicators">
    <?php echo $indicators; ?>
  </ol>

  <!-- Botones de control -->
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselGreedX" data-bs-slide="prev" style="filter: invert(1);">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Anterior</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselGreedX" data-bs-slide="next" style="filter: invert(1);">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Siguiente</span>
  </button>
</div>