<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Grix Chat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      background: #0C0027;
      margin: 0;
      font-family: sans-serif;
    }

    /* --- Estilos Responsive (Añadir al final del CSS) --- */
    @media (max-width: 600px) {

      /* 1. Mueve el wrapper al centro superior */
      #grix-wrapper {
        pointer-events: none; /* Permanece */
        display: flex; /* Asegura el flexbox */
        align-items: center; /* Centra el icono del bot */
      }
      
      /* 2. Oculta el icono del bot cuando el chat está abierto */
      #grix-wrapper.chat-abierto #grix-container,
      #chat-box.visible #grix-container {
        visibility: hidden; /* Simplemente lo ocultamos */
        opacity: 0;
        pointer-events: none;
        transition: none; /* Evita animaciones innecesarias al ocultarse */
      }

      /* 3. El chatbox ocupa la pantalla completa */
      #chat-box.visible {
        position: fixed; /* Anclado a la ventana */
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        max-width: none; /* Ignora el max-width de 400px */
        border-radius: 0; /* Sin bordes redondeados */
        /* Animación de entrada más simple para pantalla completa */
        transform: translateY(0); 
        animation: none; /* Desactivamos la animación de escala */
        opacity: 1; 
        visibility: visible;
        z-index: 10002; /* Mayor z-index para cubrir todo */
        backdrop-filter: blur(12px) brightness(0.8); /* Oscurece un poco el fondo */
        border: none !important; 
        box-shadow: none !important;
        /* Animación de entrada más simple para pantalla completa */
        transform: translateY(0); 
        animation: none;
        opacity: 1; 
        visibility: visible;
        z-index: 10002; 
        backdrop-filter: blur(12px) brightness(0.8);
      }

      /* 4. Asegura que el header sea visible */
      #chat-header {
        min-height: 50px;
        padding: 16px 12px;
      }

      /* 5. 🛑 CORRECCIÓN: Resetea el wrapper a (0,0) cuando el chat está abierto */
      #grix-wrapper.chat-abierto {
        left: 0 !important;
        top: 0 !important;
        transform: none !important; /* Elimina el centrado translateX(-50%) */
        width: 100vw; /* Asegura que cubra todo el viewport */
        height: 100vh;
      }
      
      #grix-wrapper {
        position: fixed;
        bottom: 20px;
        right: 20px;  
      }
    }

    #grix-wrapper {
      position: fixed;
      bottom: 20px; /* Se mantendrá para ser visible */
      right: 20px;  /* Se mantendrá para ser visible */
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      z-index: 9999;
      touch-action: none;
      pointer-events: none;
      transform: translate(0, 0);
      /* --- CORRECCIÓN: Ocultar hasta que JS lo posicione --- */
      visibility: hidden; 
      opacity: 0;
      transition: opacity 0.3s ease; /* Para una aparición suave */
    }

    #grix-container {
      width: 96px;
      height: 96px;
      cursor: grab;
      animation: flotar 3s ease-in-out infinite;
      z-index: 10000;
      margin-top: 10px;
      pointer-events: auto;
      transition: all 0.3s ease;
    }

    #grix-wrapper.chat-abierto #grix-container,
    #chat-box.visible #grix-container {
      position: absolute;
      top: -48px;
      left: -48px;
      width: 96px;
      height: 96px;
      margin: 0;
      animation: flotar 3s ease-in-out infinite;
      z-index: 10001;
    }

    #grix-container:active {
      cursor: grabbing;
      animation: none;
    }

    #grix-container img {
      width: 100%;
      height: 100%;
      pointer-events: none;
      user-select: none;
    }

    @keyframes flotar {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    /* --- CHAT BOX --- */
    #chat-box {
      width: 90vw;
      max-width: 400px;
      height: 55vh;
      background: rgba(26,0,61,0.8);
      backdrop-filter: blur(12px);
      border-radius: 20px;
      border: 1px solid rgba(255,0,255,0.4);
      box-shadow: 0 0 20px rgba(255, 0, 255, 0.3);
      color: white;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      font-size: 16px;
      visibility: hidden;
      opacity: 0;
      transform: scale(0.9) translateY(20px);
      pointer-events: none;
      transition: opacity 0.3s ease, transform 0.3s ease;
      z-index: 9998;
    }

    #chat-box.visible {
      visibility: visible;
      opacity: 1;
      transform: scale(1) translateY(0);
      pointer-events: auto;
      animation: abrirChatAnim 0.3s ease forwards;
    }

    @keyframes abrirChatAnim {
      0% { transform: scale(0.8) translateY(20px); opacity: 0; }
      100% { transform: scale(1) translateY(0); opacity: 1; }
    }

    #chat-header {
      background: linear-gradient(90deg, #440088, #8000FF);
      color: #fff;
      font-weight: bold;
      text-align: left; /* <-- Importante: para que Grix esté al lado del icono */
      padding: 8px 16px; 
      min-height: 66px; /* 50px (icono) + 16px (padding total) = 66px */
      display: flex; 
      align-items: center;
      gap: 10px;
      position: relative;
      border-bottom: 1px solid rgba(255,255,255,0.2);
      box-shadow: 0 2px 10px rgba(255,0,255,0.2);
      /* Nuevos estilos de alineación */
      display: flex; 
      align-items: center;
      gap: 10px; /* Espacio entre el icono y el texto */
    }

    #chat-close {
      position: absolute;
      top: 8px;
      right: 12px;
      background: none;
      border: none;
      color: white;
      font-size: 24px;
      cursor: pointer;
    }

    #chat-messages {
      flex: 1;
      padding: 12px;
      overflow-y: auto;
      font-size: 16px;
      display: flex;
      flex-direction: column;
    }

    /* --- Burbujas de mensaje --- */
    #chat-messages div {
      padding: 8px 12px;
      border-radius: 12px;
      margin-bottom: 8px;
      max-width: 80%;
      word-wrap: break-word;
    }

    /* Diferenciar usuario / Grix */
    #chat-messages div:nth-child(odd) {
      background: rgba(64,0,128,0.4);
      align-self: flex-start;
    }

    #chat-messages div:nth-child(even) {
      background: rgba(128,0,255,0.3);
      align-self: flex-end;
    }

    #chat-messages div b {
      color: #FFD6FF;
    }

    /* Scroll personalizado */
    #chat-messages::-webkit-scrollbar {
      width: 6px;
    }
    #chat-messages::-webkit-scrollbar-thumb {
      background: rgba(255,0,255,0.5);
      border-radius: 3px;
    }
    #chat-messages::-webkit-scrollbar-track {
      background: rgba(0,0,0,0.1);
    }

    #chat-messages button {
      display: block;
      width: 70%;       /* más estrecho para que no ocupe todo el ancho */
      margin: 8px 0; /* auto centra horizontalmente */
      padding: 10px 16px;
      background: linear-gradient(90deg, #8000FF, #A64DFF);
      color: #fff;
      font-weight: bold;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.2s ease;
      box-shadow: 0 2px 8px rgba(197, 4, 197, 0.3);
      text-align: center;
      align-self: flex-end;
    }

    #chat-messages button:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(204, 2, 204, 0.5);
    }

    #chat-messages button:active {
      transform: scale(0.98);
      box-shadow: 0 2px 6px rgba(184, 0, 184, 0.2);
    }

    /* Estilo para el icono de perfil */
    .grix-profile-icon {
      width:50px;
      height:50px;
      border-radius: 50%; /* Para que se vea como una foto de perfil */
      overflow: hidden;
    }

    .grix-profile-icon img {
      width: 100%;
      height: 100%;
    }
  </style>
</head>
<body>
  <div id="grix-wrapper">
    <div id="grix-container">
      <img id="grix-img" src="assets/img/Grix-durmiendo.png" alt="Grix durmiendo">
    </div>
    <div id="chat-box">
      <div id="chat-header">
        Grix
        <button id="chat-close" onclick="cerrarChat()">&times;</button>
      </div>

      <div id="chat-messages"></div>
    </div>
  </div>

  <script>
    let grixDespierto = false;
    let isDragging = false;
    let chatAbierto = false;
    let bienvenidaMostrada = false;
    const grixContainer = document.getElementById("grix-container");
    const grixImg = document.getElementById("grix-img");
    const chatBox = document.getElementById("chat-box");
    const wrapper = document.getElementById("grix-wrapper");
    const mensajes = document.getElementById("chat-messages");
    const headerDesktop = `
      Grix
      <button id="chat-close" onclick="cerrarChat()">&times;</button>
    `;

    // En móvil queremos el icono (Grix-container) DENTRO del header.
    // El icono flotante original (#grix-container) debe estar vacío o no usarse para móvil.
    const headerMobile = `
      <div class="grix-profile-icon">
        <img src="assets/img/Grix-despierto.png" alt="Grix">
      </div>
      Grix
      <button id="chat-close" onclick="cerrarChat()">&times;</button>
    `;

    const chatHeader = document.getElementById("chat-header");

    // Estructura del menú
    const menuGrix = {
      "Productos": {
        "Tipos de productos": "Ofrecemos un amplio catálogo de bienes digitales: Suscripciones a las plataformas de streaming más populares, códigos y monedas virtuales para videojuegos, giftcards (tarjetas de regalo) y accesos premium a diversas aplicaciones y servicios de software.",
        "Catálogo disponible": `Tenemos lo que necesitas: <br><br>• <b>Streaming & Música:</b> HBO Max, Netflix, Spotify, Crunchyroll, Disney+.<br>• <b>Videojuegos & Monedas:</b> Códigos para Call of Duty, Valorant, League of Legends, Minecraft Coins, Roblox, Fortnite y Keys de Minecraft.<br>• <b>Software & Apps Premium:</b> Cuentas/accesos para Canva, ChatGPT, y Discord Nitro.<br>• <b>Tarjetas de Regalo:</b> Giftcards para diversas plataformas digitales a nivel regional.`
      },
      "Pagos": {
        "Moneda y precios": "Todos nuestros precios están publicados en Pesos Argentinos (ARS) y son finales. ¡No aplicamos cargos ni impuestos sorpresa! Lo que ves es lo que pagas.",
        "Formas de pago": "Puedes pagar con todas las principales Tarjetas de Crédito/Débito (mediante nuestra pasarela segura) o a través de Transferencia Bancaria Directa.",
        "Promociones": "¡Siempre tenemos algo especial! Visita la sección 'Ofertas' de nuestra página principal. Los descuentos y promociones especiales se aplican automáticamente en el carrito de compra."
      },
      "Envíos": {
        "Entrega de productos": "La entrega es completamente digital. Recibirás tu código, key o los datos de acceso directamente en el correo electrónico que utilizaste al registrarte o realizar la compra.",
        "Tiempos de entrega": "La entrega es prácticamente inmediata. Una vez que nuestra pasarela de pago confirma tu transacción (generalmente en menos de 5 minutos), el producto digital se envía automáticamente a tu email."
      },
      "Información General": {
        "Quiénes somos": "Somos Gridex, un equipo comprometido con ofrecer la mejor experiencia y valor. Nuestro objetivo es que obtengas más por tu dinero. Te ofrecemos una plataforma donde puedes comprar una variedad de bienes y servicios digitales a un precio menor y pagar convenientemente en tu moneda local.",
        "Contacto con administradores": "Si tienes dudas complejas, problemas con tu compra o necesitas soporte técnico, puedes contactar a nuestro equipo de administradores en cualquier momento a través de nuestro correo: contacto@gridex.com (ficticio).",
        "Preguntas frecuentes": "¡Por supuesto! En esta sección respondemos a las dudas más comunes sobre la activación de productos, políticas de reembolso y soporte post-venta. Si tienes una pregunta recurrente, la encontrarás aquí."
      }
    };

    // ---------- Funciones de avatar y chat ----------
    function setEstadoGrix(despierto) {
      grixDespierto = despierto;
      grixImg.src = despierto
        ? "assets/img/Grix-despierto.png"
        : "assets/img/Grix-durmiendo.png";
    }

    window.addEventListener("load", () => {
      const isMobile = esMovil();
      
      if (!isMobile) {
        // Lógica para escritorio (cálculo de posición fijo)
        const avatarWidth = grixContainer.offsetWidth;
        const avatarHeight = grixContainer.offsetHeight;
        const finalLeft = window.innerWidth - avatarWidth - 20;
        const finalTop = window.innerHeight - avatarHeight - 20;

        wrapper.style.left = `${finalLeft}px`;
        wrapper.style.top = `${finalTop}px`;
        wrapper.style.right = "auto";
        wrapper.style.bottom = "auto";
      } else {
        // Lógica para móvil (se ancla al CSS: top: 20px, left: 50%)
        wrapper.style.right = "auto";
        wrapper.style.bottom = "auto";
      }

      // Hacer visible el wrapper ahora que está en su posición final
      wrapper.style.opacity = "1";
      wrapper.style.visibility = "visible";
    });

    // ---------- Abrir / cerrar chat ----------
    let posAntesChat = { left: null, top: null };

    function abrirChat() {
      // ... lógica de posición posAntesChat ...

      chatBox.classList.add("visible");
      wrapper.classList.add("chat-abierto");
      setEstadoGrix(true);
      chatAbierto = true;

      // LÓGICA CONDICIONAL
      if (esMovil()) {
        // 1. Establece el encabezado de móvil (con el icono de perfil)
        chatHeader.innerHTML = headerMobile; 
        
        // 2. Oculta el icono grande flotante
        grixContainer.style.visibility = 'hidden'; 
        grixContainer.style.opacity = '0';
        grixContainer.style.pointerEvents = 'none';

        // ... lógica de posición móvil (existente) ...
        setTimeout(() => {
            window.scrollTo(0, 0); 
            document.body.style.overflow = 'hidden'; 
        }, 0); 
        
      } else {
        // Establece el encabezado de escritorio (sin el icono de perfil)
        chatHeader.innerHTML = headerDesktop;
        // Asegura que el icono grande esté visible en desktop (si estaba oculto)
        grixContainer.style.visibility = 'visible';
        grixContainer.style.opacity = '1';
        grixContainer.style.pointerEvents = 'auto';
        chatHeader.style.justifyContent = 'center';

        // 2. Recalcular y ajustar la posición para evitar que se salga de la pantalla
        const anchoWrapper = wrapper.offsetWidth;
        const altoWrapper = wrapper.offsetHeight;
        
        // Obtener la posición actual (debe ser la que tenía antes de abrir)
        let newLeft = wrapper.getBoundingClientRect().left;
        let newTop = wrapper.getBoundingClientRect().top;

        const maxLeft = window.innerWidth - anchoWrapper;
        const maxTop = window.innerHeight - altoWrapper;
        
        // Aplicar la corrección de límites (colisión)
        if (newLeft > maxLeft) newLeft = maxLeft;
        if (newTop > maxTop) newTop = maxTop;
        if (newLeft < 0) newLeft = 0;
        if (newTop < 0) newTop = 0;

        // 3. Aplicar la nueva posición
        wrapper.style.left = `${newLeft}px`;
        wrapper.style.top = `${newTop}px`;
        wrapper.style.right = "auto";
        wrapper.style.bottom = "auto";
      }
      
      if (!bienvenidaMostrada) {
        mostrarMenuPrincipal();
        bienvenidaMostrada = true;
      }
    }

    function cerrarChat() {
      chatBox.classList.remove("visible");
      wrapper.classList.remove("chat-abierto");
      setEstadoGrix(false);
      chatAbierto = false;
      
      if (esMovil()) {
        // Reactivar el scroll del cuerpo de la página
        document.body.style.overflow = 'auto';
        // Restablece el encabezado a desktop/default (aunque el chatbox está oculto)
        chatHeader.innerHTML = headerDesktop; 
        // Muestra el icono grande flotante de nuevo
        grixContainer.style.visibility = 'visible'; 
        grixContainer.style.opacity = '1';
        grixContainer.style.pointerEvents = 'auto';
      }
      
      // Mantenemos la posición final del arrastre
      const rect = wrapper.getBoundingClientRect();
      wrapper.style.left = `${rect.left}px`;
      wrapper.style.top = `${rect.top}px`;
      wrapper.style.right = "auto";
      wrapper.style.bottom = "auto";
    }

    // ---------- Mostrar menú principal ----------
    function mostrarMenuPrincipal() {
      mensajes.innerHTML = `<div><b>Grix:</b> ¡Hola! ¿En qué te ayudo?</div>`;
      for (let tema in menuGrix) {
        const btn = document.createElement("button");
        btn.textContent = tema;
        btn.style.margin = "5px 0";
        btn.onclick = () => mostrarSubmenu(tema);
        mensajes.appendChild(btn);
      }
      mensajes.scrollTop = mensajes.scrollHeight;
    }

    // ---------- Mostrar submenu ----------
    function mostrarSubmenu(tema) {
      mensajes.innerHTML = `<div><b>Grix:</b> Elegiste <b>${tema}</b>, ahora selecciona una opción:</div>`;
      for (let sub in menuGrix[tema]) {
        const btn = document.createElement("button");
        btn.textContent = sub;
        btn.style.margin = "5px 0";
        btn.onclick = () => mostrarRespuesta(tema, sub);
        mensajes.appendChild(btn);
      }
      // Botón para volver
      const volver = document.createElement("button");
      volver.textContent = "← Volver";
      volver.style.margin = "5px 0";
      volver.onclick = mostrarMenuPrincipal;
      mensajes.appendChild(volver);
      mensajes.scrollTop = mensajes.scrollHeight;
    }

    // ---------- Mostrar respuesta ----------
    function mostrarRespuesta(tema, sub) {
      mensajes.innerHTML = `<div><b>Grix:</b> ${menuGrix[tema][sub]}</div>`;
      // Botón para volver al submenu
      const volver = document.createElement("button");
      volver.textContent = "← Volver al menú";
      volver.style.margin = "5px 0";
      volver.onclick = () => mostrarSubmenu(tema);
      mensajes.appendChild(volver);
      mensajes.scrollTop = mensajes.scrollHeight;
    }

    // ---------- Drag del avatar ----------
    let offsetX = 0, offsetY = 0, wrapperRectDrag = null, dragStartX = 0, dragStartY = 0;
    let dragThreshold = 15;

    grixContainer.addEventListener("pointerup", (e) => {
      if (chatAbierto) return; // Si ya está abierto, no hacemos nada

      const dx = Math.abs(e.clientX - dragStartX);
      const dy = Math.abs(e.clientY - dragStartY);

      // Si el movimiento fue mínimo, consideramos que fue un clic/tap.
      if (dx < dragThreshold && dy < dragThreshold) {
        abrirChat();
      }
      // Si fue un arrastre, isDragging ya se habrá puesto en true y simplemente no abre el chat.
    });

    wrapper.addEventListener("pointerdown", (e) => {
      isDragging = false;
      wrapperRectDrag = wrapper.getBoundingClientRect();
      offsetX = e.clientX - wrapperRectDrag.left;
      offsetY = e.clientY - wrapperRectDrag.top;
      dragStartX = e.clientX;
      dragStartY = e.clientY;

      function moveHandler(e) {
        //CORRECCIÓN REVISADA: Si es móvil Y el chat está abierto, NO PERMITIMOS EL ARRASTRE.
        if (esMovil() && chatAbierto) {
          isDragging = false;
          return; 
        }
        
        isDragging = true;
        let newLeft = e.clientX - offsetX;
        let newTop = e.clientY - offsetY;
        const ancho = chatAbierto ? wrapper.offsetWidth : grixContainer.offsetWidth;
        const alto = chatAbierto ? wrapper.offsetHeight : grixContainer.offsetHeight;
        const maxLeft = window.innerWidth - ancho;
        const maxTop = window.innerHeight - alto;
        if (newLeft < 0) newLeft = 0;
        if (newTop < 0) newTop = 0;
        if (newLeft > maxLeft) newLeft = maxLeft;
        if (newTop > maxTop) newTop = maxTop;
        wrapper.style.left = `${newLeft}px`;
        wrapper.style.top = `${newTop}px`;
        wrapper.style.right = "auto";
        wrapper.style.bottom = "auto";
        wrapper.style.position = "fixed";
      }

      function upHandler() {
        document.removeEventListener("pointermove", moveHandler);
        document.removeEventListener("pointerup", upHandler);
        setTimeout(() => { if (!isDragging) return; isDragging = false; }, 100);
      }

      document.addEventListener("pointermove", moveHandler);
      document.addEventListener("pointerup", upHandler);
    });

    function esMovil() {
      return window.matchMedia("(max-width: 600px)").matches;
    }
  </script>
</body>
</html>