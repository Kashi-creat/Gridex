-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-10-2025 a las 20:12:55
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `giftcard_web`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrusel`
--

CREATE TABLE `carrusel` (
  `id` int(11) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `alt_text` varchar(100) DEFAULT NULL COMMENT 'texto alternativo',
  `activo` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrusel`
--

INSERT INTO `carrusel` (`id`, `imagen`, `alt_text`, `activo`) VALUES
(11, 'https://cdn.hashnode.com/res/hashnode/image/upload/v1623827644552/3ZlQ6H-ru.jpeg', 'netflix-banner', 1),
(12, 'https://www.sarbidemusic.com/wp-content/uploads/2021/01/spotify-ad-insertion-1200x480.png', 'spotify-banner', 1),
(13, 'https://fiverr-res.cloudinary.com/images/q_auto,f_auto/gigs/152028884/original/9648f591a0584eb58be3880c5099c8cd118c9676/make-you-professional-valorant-banner-header.png', 'valorant-banner', 1),
(14, 'https://pbs.twimg.com/media/E629ivCVoAAsHrU.jpg:large', 'lol-banner', 1),
(15, 'https://www.shutterstock.com/image-illustration/chatgpt-logo-seen-on-banner-260nw-2320762729.jpg', 'chatgpt-banner', 1),
(16, 'https://www.ytgraphics.com/wp-content/uploads/2015/11/cod-black-ops-3.jpg', 'cod-banner', 1),
(17, 'https://trebolcodes.com/wp-content/uploads/fortnitebanner.jpg', 'fortnite-banner', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos`
--

CREATE TABLE `codigos` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `entregado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedidos`
--

CREATE TABLE `detalles_pedidos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad_id` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado` varchar(50) NOT NULL DEFAULT 'pendiente',
  `metodo_pago` varchar(50) NOT NULL DEFAULT 'transferencia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `imagen_url` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `imagen_url`, `stock`, `activo`) VALUES
(3, 'Nitro', 'Discord Nitro 1 mes', 1500.00, 'Apps y software', 'https://cdn.prod.website-files.com/5f9072399b2640f14d6a2bf4/666cbcfd14d3f28b5309dd93_image5.png', 100, 1),
(4, 'Nitro', 'Discord Nitro 12 meses', 16000.00, 'Apps y software', 'https://cdn.prod.website-files.com/5f9072399b2640f14d6a2bf4/666cbcfd14d3f28b5309dd93_image5.png', 100, 1),
(5, 'Canva', 'Canva Pro 1 mes', 1800.00, 'Apps y software', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQQzF91O3aVqpVACHUa3EM2_3l0gI4ZlmmMAg&s', 100, 1),
(6, 'Canva', 'Canva Pro 12 meses', 19000.00, 'Apps y software', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQQzF91O3aVqpVACHUa3EM2_3l0gI4ZlmmMAg&s', 100, 1),
(7, 'ChatGPT', 'ChatGPT Plus 1 mes', 6500.00, 'Apps y software', 'https://www.zdnet.com/a/img/resize/677449abecd5bed60206a3e9343002f02ad85ac2/2023/08/02/da929659-3069-4f1e-9bfe-d7794231b9a3/gettyimages-1246813934.jpg?auto=webp&width=1280', 100, 1),
(8, 'Valorant', '1000 VP', 2000.00, 'Juegos', 'https://www.games2egypt.com/Images/Products/119985?fileFormat=1', 100, 1),
(9, 'Valorant', '2050 VP', 4000.00, 'Juegos', 'https://www.games2egypt.com/Images/Products/119985?fileFormat=1', 100, 1),
(10, 'Valorant', '3650 VP', 7000.00, 'Juegos', 'https://www.games2egypt.com/Images/Products/119985?fileFormat=1', 100, 1),
(11, 'Valorant', '5350 VP', 10000.00, 'Juegos', 'https://www.games2egypt.com/Images/Products/119985?fileFormat=1', 100, 1),
(12, 'Valorant', '11000 VP', 18000.00, 'Juegos', 'https://www.games2egypt.com/Images/Products/119985?fileFormat=1', 100, 1),
(13, 'League of Legends', '720 RP', 1600.00, 'Juegos', 'https://bonoxs.com/_next/image?url=https%3A%2F%2Fprod-bnx-public.s3.us-east-1.amazonaws.com%2Fmedia%2Fcatalog%2Fproduct%2Fl%2Fo%2Flol_2023_240x300_9.jpg&w=384&q=75', 100, 1),
(14, 'League of Legends', '1380 RP', 3000.00, 'Juegos', 'https://bonoxs.com/_next/image?url=https%3A%2F%2Fprod-bnx-public.s3.us-east-1.amazonaws.com%2Fmedia%2Fcatalog%2Fproduct%2Fl%2Fo%2Flol_2023_240x300_9.jpg&w=384&q=75', 100, 1),
(15, 'League of Legends', '2800', 5500.00, 'Juegos', 'https://bonoxs.com/_next/image?url=https%3A%2F%2Fprod-bnx-public.s3.us-east-1.amazonaws.com%2Fmedia%2Fcatalog%2Fproduct%2Fl%2Fo%2Flol_2023_240x300_9.jpg&w=384&q=75', 100, 1),
(16, 'League of Legends', '5000 RP', 9000.00, 'Juegos', 'https://bonoxs.com/_next/image?url=https%3A%2F%2Fprod-bnx-public.s3.us-east-1.amazonaws.com%2Fmedia%2Fcatalog%2Fproduct%2Fl%2Fo%2Flol_2023_240x300_9.jpg&w=384&q=75', 100, 1),
(17, 'Fortnite', '1000 V-Bucks', 2200.00, 'Juegos', 'https://cdn1.epicgames.com/offer/fn/FNECO_32-00_VbuckStoreArtUpdate_2800_EGS_1200x1600_1200x1600-380718e8fb23306b6e8a801d27880104', 100, 1),
(18, 'Fortnite', '2800 V-Bucks', 5300.00, 'Juegos', 'https://cdn1.epicgames.com/offer/fn/FNECO_32-00_VbuckStoreArtUpdate_2800_EGS_1200x1600_1200x1600-380718e8fb23306b6e8a801d27880104', 100, 1),
(19, 'Fortnite', '5000 V-Bucks', 8900.00, 'Juegos', 'https://cdn1.epicgames.com/offer/fn/FNECO_32-00_VbuckStoreArtUpdate_2800_EGS_1200x1600_1200x1600-380718e8fb23306b6e8a801d27880104', 100, 1),
(20, 'Call of Duty', '1100 COD Points', 2300.00, 'Juegos', 'https://dexstoore.com/wp-content/uploads/2025/01/CoD-Points-Price-how-to-buy-spend.png', 100, 1),
(21, 'Call of Duty', '2400 COD Points', 4800.00, 'Juegos', 'https://dexstoore.com/wp-content/uploads/2025/01/CoD-Points-Price-how-to-buy-spend.png', 100, 1),
(22, 'Call of Duty', '5000 COD Points', 9200.00, 'Juegos', 'https://dexstoore.com/wp-content/uploads/2025/01/CoD-Points-Price-how-to-buy-spend.png', 100, 1),
(23, 'Roblox', '400 Robux', 1000.00, 'Juegos', 'https://assets-prd.ignimgs.com/2024/07/16/prime-day-roblox-gift-card-deal-1721138283214.jpg', 100, 1),
(24, 'Roblox', '800 Robux', 1800.00, 'Juegos', 'https://assets-prd.ignimgs.com/2024/07/16/prime-day-roblox-gift-card-deal-1721138283214.jpg', 100, 1),
(25, 'Roblox', '1700 Robux', 3500.00, 'Juegos', 'https://assets-prd.ignimgs.com/2024/07/16/prime-day-roblox-gift-card-deal-1721138283214.jpg', 100, 1),
(26, 'Minecraft', 'Java Edition Key', 7000.00, 'Juegos', 'https://images-cdn.ubuy.com.ar/6495c88f4a9e0c5cfa4865a4-minecraft-java-edition-pc-game-key.jpg', 100, 1),
(27, 'Minecraft', 'Windows 10 Edition Key', 6000.00, 'Juegos', 'https://products.eneba.games/resized-products/mp6a3WpBEg3oX4iidlH8NYskzTrpcgj6aAFNe5BqbS0_350x200_2x-0.jpeg', 100, 1),
(28, 'Minecraft', '1720 Minecoins', 2500.00, 'Juegos', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnQDVU_YCSq3BE326U9cEC6uY-qNjG8uqDhw&s', 100, 1),
(29, 'Netflix', 'Giftcard $1000', 1000.00, 'Streaming', 'https://media.ambito.com/p/cabec2c9bacc51f4d45a9228ddcb351d/adjuntos/239/imagenes/039/317/0039317632/375x211/smart/netflixjpg.jpg', 100, 1),
(30, 'Netflix', 'Giftcard $2000', 2000.00, 'Streaming', 'https://media.ambito.com/p/cabec2c9bacc51f4d45a9228ddcb351d/adjuntos/239/imagenes/039/317/0039317632/375x211/smart/netflixjpg.jpg', 100, 1),
(31, 'Spotify', '1 mes Premium', 1300.00, 'Streaming', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSZ1ukpFVf_h8KIazSs3D5VDoG7qHM0dJJNJA&s', 100, 1),
(32, 'Spotify', '3 mese Premium', 3800.00, 'Streaming', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSZ1ukpFVf_h8KIazSs3D5VDoG7qHM0dJJNJA&s', 100, 1),
(33, 'Spotify', 'Tarjeta Regalo $1000', 1000.00, 'Streaming', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSZ1ukpFVf_h8KIazSs3D5VDoG7qHM0dJJNJA&s', 100, 1),
(34, 'Disney+', '1 mes suscripción', 1600.00, 'Streaming', 'https://lumiere-a.akamaihd.net/v1/images/au_featuretitle_disneyplus_logotile_m_57529a73.png', 100, 1),
(35, 'Disney+', '3 meses suscripción', 4500.00, 'Streaming', 'https://lumiere-a.akamaihd.net/v1/images/au_featuretitle_disneyplus_logotile_m_57529a73.png', 100, 1),
(36, 'Crunchyroll', '1 mes Mega Fan', 1200.00, 'Streaming', 'https://paas-file-pro.igv.com/shop/65359e9b1d2af611641d4498.png', 100, 1),
(37, 'Crunchyroll', '3 meses Mega Fan', 3200.00, 'Streaming', 'https://paas-file-pro.igv.com/shop/65359e9b1d2af611641d4498.png', 100, 1),
(38, 'HBO Max', '1 mes suscripción', 1800.00, 'Streaming', 'https://img.pccomponentes.com/pcblog/9756/cuanto-cuesta-hbo-estos-son-sus-planes-y-tarifas-actualizados.jpg', 100, 1),
(39, 'HBO Max', '3 mese suscripción', 4900.00, 'Streaming', 'https://img.pccomponentes.com/pcblog/9756/cuanto-cuesta-hbo-estos-son-sus-planes-y-tarifas-actualizados.jpg', 100, 1),
(40, 'Google Play', 'Google Play $1000', 1000.00, 'Giftcards', 'https://products.eneba.games/resized-products/lBRGttqRV5PKI3M5hTegykgoORMG6pyUM7AcuvKNUmo_350x200_2x-0.jpeg', 100, 1),
(41, 'Google Play', 'Google Play $2000', 2000.00, 'Giftcards', 'https://products.eneba.games/resized-products/lBRGttqRV5PKI3M5hTegykgoORMG6pyUM7AcuvKNUmo_350x200_2x-0.jpeg', 100, 1),
(42, 'Google Play', 'Google Play $5000', 5000.00, 'Giftcards', 'https://products.eneba.games/resized-products/lBRGttqRV5PKI3M5hTegykgoORMG6pyUM7AcuvKNUmo_350x200_2x-0.jpeg', 100, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL DEFAULT '''cliente''' COMMENT 'tipo de usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `email`, `contrasena`, `rol`) VALUES
(1, 'Kashi', 'kashikoi082@gmail.com', '$2y$10$FXxfwrmVwg2KaUX9UE19nu1p0DF3odmdMb.Zj.VVeA5AkNxyoMpku', 'usuario'),
(4, 'Matias', 'cocamatias966@gmail.com', '$2y$10$R0W3vR9RMdtAFH.Pm3JVBefgLGjvSp1FegngAwMOjLExIZKbv1jzG', 'trabajador'),
(5, 'admin', 'admin@gmail.com', '$2y$10$k6F7ujFltuWm3ri7jWJnRuE0lax4V7Sy1KTH3qQolZpyhK9TPK6D6', 'trabajador'),
(7, 'sam', 'sam@gmail.com', '$2y$10$OL409MD5FPd7OfLfhlI48exxQKWnoSO0u/wXpaWa2h65leq7pOTqO', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `carrusel`
--
ALTER TABLE `carrusel`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `codigos`
--
ALTER TABLE `codigos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `detalles_pedidos`
--
ALTER TABLE `detalles_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `carrusel`
--
ALTER TABLE `carrusel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `codigos`
--
ALTER TABLE `codigos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles_pedidos`
--
ALTER TABLE `detalles_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `codigos`
--
ALTER TABLE `codigos`
  ADD CONSTRAINT `codigos_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `detalles_pedidos`
--
ALTER TABLE `detalles_pedidos`
  ADD CONSTRAINT `detalles_pedidos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `detalles_pedidos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
