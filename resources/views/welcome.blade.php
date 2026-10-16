<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MillennialLife</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700,800,900&display=swap" rel="stylesheet" />

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #ffffff;
            color: #1e293b;
            overflow-x: hidden;
            line-height: 1.5;
        }

        /* NAVBAR */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #f1f5f9;
            padding: 0.4rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .btn-login {
            color: #475569;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            transition: color 0.2s;
        }
        .btn-login:hover { color: #4f46e5; }
        .btn-register {
            background-color: #4f46e5;
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 0.6rem 1.4rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
            transition: all 0.2s;
        }
        .btn-register:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
        }

        /* HERO  */
    
        .hero-section {
            position: relative;
            width: 100%;
            min-height: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 0rem !important;
            padding-bottom: 1rem;
            padding-left: 1rem;
            padding-right: 1rem;
            background: #ffffff;
        }

        .hero-img-container {
            width: 100%;
            max-width: 1100px;
            margin-top: 0 !important; 
            margin-bottom: 1rem;
            position: relative;
        }

        .hero-img-container img {
            width: 100%;
            height: auto;
            max-height: 80vh;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #eef2ff;
            color: #4f46e5;
            padding: 0.35rem 0.9rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
        }
        .hero-title {
            font-size: 2.25rem;
            font-weight: 900;
            color: #0f172a;
            text-align: center;
            line-height: 1.15;
            margin-bottom: 0.5rem;
        }
        .hero-desc {
            font-size: 1.05rem;
            color: #64748b;
            text-align: center;
            max-width: 650px;
            margin: 0 auto 1.5rem auto;
        }

        /* SECCIONES DE VIDEOS ALTERNADOS */
        .video-section {
            max-width: 1100px;
            margin: 3.5rem auto;
            padding: 0 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 5rem;
        }
        .video-row {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2.5rem;
        }
        @media (min-width: 768px) {
            .video-row { flex-direction: row; }
            .video-row.reverse { flex-direction: row-reverse; }
        }
        .video-box {
            flex: 1;
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border-radius: 1.5rem;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .video-box video {
            width: 100%;
            height: auto;
            display: block;
        }
        .video-text {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .video-tag {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #4f46e5;
        }
        .video-text h2 {
            font-size: 1.9rem;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.2;
        }
        .video-text p {
            color: #64748b;
            font-size: 1rem;
            line-height: 1.6;
        }

        .video-header {
            text-align: center;
            margin-bottom: -2rem;
        }

        .video-header .hero-title {
            margin-bottom: 0.35rem; 
        }

        .video-header .hero-desc {
            margin-bottom: 0;
        }
        /* CARRUSEL */
        .carousel-section {
            background-color: #ffffff;
            padding: 5rem 1.5rem;
            text-align: center;
        }
        .carousel-wrapper {
            max-width: 800px;
            margin: 2.5rem auto 0 auto;
            background: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            padding: 2.5rem;
            position: relative;
            min-height: 380px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .carousel-slide {
            display: none;
            flex-direction: column;
            align-items: center;
            gap: 1.25rem;
            animation: fadeIn 0.4s ease-in-out;
        }
        .carousel-slide.active {
            display: flex;
        }
        .carousel-slide img {
            max-height: 240px;
            width: auto;
            object-fit: contain;
        }
        .carousel-slide h3 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
        }
        .carousel-slide p {
            font-size: 0.95rem;
            color: #64748b;
            max-width: 500px;
        }
        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: #ffffff;
            border: 1px solid #cbd5e1;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            transition: all 0.2s;
            font-weight: bold;
            font-size: 1.1rem;
            color: #334155;
        }
        .carousel-btn:hover {
            background: #4f46e5;
            color: #ffffff;
            border-color: #4f46e5;
        }
        .carousel-btn.prev { left: -1.25rem; }
        .carousel-btn.next { right: -1.25rem; }
        .carousel-dots {
            display: flex;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }
        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #cbd5e1;
            cursor: pointer;
            transition: all 0.2s;
        }
        .dot.active {
            width: 24px;
            border-radius: 9999px;
            background: #4f46e5;
        }

        /* ACENTOS AMBIENTALES DISCRETOS */
        .decor-blob {
            position: absolute;
            pointer-events: none;
            z-index: 0;
            opacity: 0.35;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <a href="{{ url('/') }}" class="nav-brand">
            <img src="{{ asset('logo.png') }}" alt="Logo" style="height: 38px; width: auto;">
            <img src="{{ asset('logo_texto.png') }}" alt="MillennialLife" style="height: 24px; width: auto;">
        </a>

        <div class="nav-links">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-register">Ir al Dashboard →</a>
                @else
                    <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-register">Registrarme</a>
                    @endif
                @endauth
            @endif
        </div>
    </header>

    <!-- 1. HERO SECTION  -->
    <section class="hero-section">
      
        <!-- Imagen Hero -->
        <div class="hero-img-container">
            <img src="{{ asset('assets/Hero.png') }}" alt="MillennialLife Plataforma">
        </div>

        <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 0.5rem;">
            <a href="{{ route('register') }}" class="btn-register" style="padding: 0.75rem 2rem; font-size: 1rem;">
                Comenzar gratis ahora
            </a>
        </div>
    </section>

        
    <!-- 2. SECCIÓN DE FUNCIONES: VIDEOS ANIMADOS Y EXPLICACIÓN -->
    <section class="video-section">
        
        <div class="video-header">
        <h2 class="hero-title">La herramienta pensada para sobrevivir a la vida adulta sin estrés ni desorden.</h2>
        <p class="hero-desc">
            Gestiona tus recibos, trámites, citas médicas y finanzas en un solo lugar.
        </p>
    </div>

        <!-- Bloque 1: Organiza (Video a la izquierda, texto a la derecha) -->
        <div class="video-row">
            <div class="video-box">
                <video autoplay loop muted playsinline>
                    <source src="{{ asset('assets/organiza.mp4') }}" type="video/mp4">
                </video>
            </div>
            <div class="video-text">
                <span class="video-tag">Paso 1: Organización</span>
                <h2>Reúne todos tus recibos y pagos recurrentes</h2>
                <p>
                    Registra servicios como luz, agua, gas e internet. MillennialLife te indica con precisión las fechas de corte para que nunca más pagues recargos ni olvides una fecha límite.
                </p>
            </div>
        </div>

        <!-- Bloque 2: Paga (Texto a la izquierda, video a la derecha) -->
        <div class="video-row reverse">
            <div class="video-box">
                <video autoplay loop muted playsinline>
                    <source src="{{ asset('assets/paga.mp4') }}" type="video/mp4">
                </video>
            </div>
            <div class="video-text">
                <span class="video-tag" style="color: #059669;">Paso 2: Liquidación</span>
                <h2>Paga tus servicios por quincena</h2>
                <p>
                    Guarda tus gastos pendientes en tu bandeja de espera. Al cobrar tu quincena, liquida los servicios en un solo flujo rápido y seguro con tu tarjeta...¡desde casa!
                </p>
            </div>
        </div>

        <!-- Bloque 3: Administra (Video a la izquierda, texto a la derecha) -->
        <div class="video-row">
            <div class="video-box">
                <video autoplay loop muted playsinline>
                    <source src="{{ asset('assets/administra.mp4') }}" type="video/mp4">
                </video>
            </div>
            <div class="video-text">
                <span class="video-tag" style="color: #7c3aed;">Paso 3: Claridad Total</span>
                <h2>Expediente de salud, finanzas y reportes PDF</h2>
                <p>
                    Archiva recetas y comprobantes escaneados, monitorea cómo suben o bajan tus consumos con gráficas estadísticas y descarga reportes en PDF con un solo clic.
                </p>
            </div>
        </div>

    </section>

    <!-- 3. CARRUSEL DE MÓDULOS CON NAVEGACIÓN LIMPIA -->
    <section class="carousel-section">
        <span class="hero-badge" style="background: #ffffff; border: 1px solid #e2e8f0;">Descubre los módulos</span>
        <h2 style="font-size: 2rem; font-weight: 900; color: #0f172a; margin-top: 0.5rem;">Todo lo que incluye MillennialLife</h2>

        <div class="carousel-wrapper">
            <!-- Botones de flecha -->
            <button class="carousel-btn prev" onclick="changeSlide(-1)">&#10094;</button>
            <button class="carousel-btn next" onclick="changeSlide(1)">&#10095;</button>

            <!-- Slide 1 -->
            <div class="carousel-slide">
                <img src="{{ asset('assets/carrusel-appintegral.png') }}" alt="App Integral Adulting">
                <h3>App Integral Adulting</h3>
                <p>La herramienta pensada para sobrevivir a la vida adulta sin estrés ni desorden.</p>
            </div>
            
            <!-- Slide 2 -->
            <div class="carousel-slide">
                <img src="{{ asset('assets/carrusel-guarda.png') }}" alt="Guarda tus Comprobantes">
                <h3>Guarda tus Comprobantes</h3>
                <p>Carga fotos de tus tickets, recibos pagados y recetas médicas... ¡O lo que tu quieras!</p>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-slide">
                <img src="{{ asset('assets/carrusel-administra.png') }}" alt="Administración Inteligente">
                <h3>Administración Financiera</h3>
                <p>Analiza el histórico de tus pagos y detecta automáticamente si estás ahorrando o gastando de más.</p>
            </div>

            <!-- Slide 4 -->
            <div class="carousel-slide active">
                <img src="{{ asset('assets/carrusel-organizacion.png') }}" alt="Organización Total">
                <h3>Organización Total</h3>
                <p>Controla tus fechas de corte, días de vencimiento y montos estimados de tus servicios mensuales.</p>
            </div>

            <!-- Puntos de navegación -->
            <div class="carousel-dots">
                <span class="dot active" onclick="setSlide(0)"></span>
                <span class="dot" onclick="setSlide(1)"></span>
                <span class="dot" onclick="setSlide(2)"></span>
                <span class="dot" onclick="setSlide(3)"></span>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer style="padding: 2.5rem 1.5rem; background: #ffffff; text-align: center; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <img src="{{ asset('logo.png') }}" alt="Logo" style="height: 26px;">
            <img src="{{ asset('logo_texto.png') }}" alt="MillennialLife" style="height: 24px; width: auto;">
        </div>
        <p style="font-size: 0.85rem; color: #94a3b8;">
            &copy; {{ date('Y') }} MillennialLife. Tu Asistente Integral de Adulting & Finanzas .
        </p>
    </footer>

    <!-- SCRIPT DEL CARRUSEL (Puro JS, sin depender de librerías externas) -->
    <script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.dot');
    const wrapper = document.querySelector('.carousel-wrapper');
    let autoSlideTimer = null;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
            dots[i].classList.toggle('active', i === index);
        });
        currentSlide = index;
    }

    function changeSlide(direction) {
        let nextIndex = (currentSlide + direction + slides.length) % slides.length;
        showSlide(nextIndex);
    }

    function setSlide(index) {
        showSlide(index);
    }

    // Iniciar avance automático cada 4 segundos (4000ms)
    function startAutoSlide() {
        stopAutoSlide();
        autoSlideTimer = setInterval(() => {
            changeSlide(1);
        }, 4000);
    }

    // Detener temporizador
    function stopAutoSlide() {
        if (autoSlideTimer) {
            clearInterval(autoSlideTimer);
            autoSlideTimer = null;
        }
    }

    // Arrancar el ciclo automático
    startAutoSlide();

    // Pausar cuando el usuario pasa el mouse encima para leer y reanudar al salir
    if (wrapper) {
        wrapper.addEventListener('mouseenter', stopAutoSlide);
        wrapper.addEventListener('mouseleave', startAutoSlide);
        // Soporte táctil en móviles
        wrapper.addEventListener('touchstart', stopAutoSlide, { passive: true });
        wrapper.addEventListener('touchend', startAutoSlide, { passive: true });
    }
</script>

</body>
</html>

