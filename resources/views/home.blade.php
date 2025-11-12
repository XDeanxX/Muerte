<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#155dcf">
    <meta name="description"
        content="Consejo Municipal de Bruzual - Comprometidos con el desarrollo y bienestar de nuestra comunidad">
    <title>Consejo Municipal de Bruzual</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="https://unpkg.com/mapkick/dist/mapkick.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* Navbar Styles */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1.5rem 0;
            background: transparent;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar.scrolled {
            top: 1rem;
            left: 1rem;
            right: 1rem;
            padding: 0.75rem 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(21, 93, 207, 0.15);
        }

        .navbar-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            font-family: 'Space Grotesk', sans-serif;
            box-shadow: 0 4px 12px rgba(21, 93, 207, 0.3);
            transition: transform 0.3s ease;
        }

        .navbar.scrolled .logo-icon {
            width: 42px;
            height: 42px;
            font-size: 1rem;
        }

        .logo-icon:hover {
            transform: scale(1.05);
        }

        .logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            transition: color 0.3s ease;
        }

        .navbar.scrolled .logo-text {
            color: #457cd3;
            font-size: 1.1rem;
        }

        .navbar-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .navbar-menu li {
            margin: 0;
        }

        .nav-link {
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            color: white;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: background-color 0.3s ease, color 0.3s ease;
            position: relative;
            display: inline-block;
        }

        .navbar.scrolled .nav-link {
            color: #334155;
        }

        .nav-link:hover {
            background-color: rgba(21, 93, 207, 0.1);
            color: #155dcf;
        }

        .navbar.scrolled .nav-link:hover {
            background-color: rgba(21, 93, 207, 0.1);
            color: #155dcf;
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0a3d7a 0%, #155dcf 50%, #1a74e8 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-content {
            text-align: center;
            color: white;
            z-index: 10;
            padding: 2rem;
            max-width: 900px;
        }

        .hero-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 400;
            margin-bottom: 2.5rem;
            opacity: 0.95;
            line-height: 1.6;
        }

        .hero-button {
            background: white;
            color: #155dcf;
            border: none;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .hero-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
        }

        /* Section Styles */
        .section-light {
            padding: 6rem 0;
            background: #f8fafc;
        }

        .section-dark {
            padding: 6rem 0;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            text-align: center;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .section-title.text-white {
            color: white;
        }

        .section-divider {
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #155dcf, #1a74e8);
            margin: 0 auto 4rem;
            border-radius: 2px;
        }

        /* Grid Layouts */
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        /* Content Cards */
        .content-card {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .content-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(21, 93, 207, 0.15);
        }

        .icon-wrapper {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #155dcf 0%, #1a74e8 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: white;
            box-shadow: 0 4px 16px rgba(21, 93, 207, 0.3);
        }

        .content-card h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.75rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .content-card p {
            color: #64748b;
            line-height: 1.7;
            font-size: 1.05rem;
        }

        /* Service Cards */
        .service-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(8px);
            padding: 2rem;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease, border-color 0.3s ease;
        }

        .service-card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-8px);
        }

        .service-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #155dcf 0%, #1a74e8 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            box-shadow: 0 4px 16px rgba(21, 93, 207, 0.4);
        }

        .service-card h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            margin-bottom: 1rem;
        }

        .service-card p {
            color: #cbd5e1;
            line-height: 1.7;
            font-size: 1rem;
        }

        /* Project Cards */
        .project-card {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .project-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(21, 93, 207, 0.15);
        }

        .project-number {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 4rem;
            font-weight: 700;
            color: rgba(21, 93, 207, 0.08);
            font-family: 'Space Grotesk', sans-serif;
        }

        .project-card h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .project-card p {
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #155dcf 0%, #1a74e8 100%);
            border-radius: 4px;
            transition: width 0.6s ease;
        }

        .progress-label {
            font-size: 0.9rem;
            color: #155dcf;
            font-weight: 600;
        }

        /* Team Cards */
        .team-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(8px);
            padding: 2.5rem;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease, border-color 0.3s ease;
        }

        .team-card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-8px);
        }

        .team-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #155dcf 0%, #1a74e8 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            color: white;
            box-shadow: 0 4px 16px rgba(21, 93, 207, 0.4);
        }

        .team-card h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.75rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }

        .team-card .role {
            color: #94a3b8;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .team-card .description {
            color: #cbd5e1;
            line-height: 1.7;
            font-size: 1rem;
        }

        /* Contact Section */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 3rem;
            align-items: start;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .contact-item {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #155dcf 0%, #1a74e8 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(21, 93, 207, 0.3);
        }

        .contact-item h4 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .contact-item p {
            color: #64748b;
            line-height: 1.7;
            font-size: 1rem;
        }

        /* Contact Form */
        .contact-form-wrapper {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group textarea {
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #155dcf;
            box-shadow: 0 0 0 3px rgba(21, 93, 207, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .submit-button {
            background: linear-gradient(135deg, #155dcf 0%, #1a74e8 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 12px rgba(21, 93, 207, 0.3);
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(21, 93, 207, 0.4);
        }

        /* Footer */
        .footer {
            background: #0f172a;
            color: white;
            padding: 4rem 0 2rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-section h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: white;
        }

        .footer-section h4 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: white;
        }

        .footer-section p {
            color: #94a3b8;
            line-height: 1.7;
        }

        .footer-section ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .footer-section ul a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .footer-section ul a:hover {
            color: #155dcf;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
            text-decoration: none;
        }

        .social-links a:hover {
            background: #155dcf;
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            font-size: 0.95rem;
        }

        /* Intersection Observer Animations */
        .observe-section {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .observe-section.animate-in {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-menu {
                display: none;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.25rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .navbar.scrolled {
                left: 0.5rem;
                right: 0.5rem;
            }

            .section-light,
            .section-dark {
                padding: 4rem 0;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .logo-text {
                font-size: 1rem;
            }

            .navbar.scrolled .logo-text {
                font-size: 0.9rem;
            }
        }
    </style>

</head>

<body>
    <nav id="navbar" class="navbar" data-testid="navbar">
        <div class="navbar-container">
            <div class="navbar-logo" data-testid="navbar-logo">
                <img src="{{asset('img/isotipo.png')}}" alt="" class="logo-icon">
                <span class="logo-text">Consejo Municipal de Bruzual</span>
            </div>

            <ul class="navbar-menu">
                <li><a href="#inicio" class="nav-link" data-testid="nav-link-inicio">Inicio</a></li>
                <li><a href="#nosotros" class="nav-link" data-testid="nav-link-nosotros">Nosotros</a></li>
                <li><a href="#servicios" class="nav-link" data-testid="nav-link-servicios">Servicios</a></li>
                <li><a href="#proyectos" class="nav-link" data-testid="nav-link-proyectos">Proyectos</a></li>
                <li><a href="#organizacion" class="nav-link" data-testid="nav-link-organizacion">Organización</a></li>
                <li><a href="#contacto" class="nav-link" data-testid="nav-link-contacto">Contacto</a></li>
            </ul>
        </div>
    </nav>

    <section id="inicio" class="hero-section" data-testid="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content observe-section">
            <h1 class="hero-title" data-testid="hero-title">Consejo Municipal de Bruzual</h1>
            <p class="hero-subtitle" data-testid="hero-subtitle">Trabajando por el desarrollo y bienestar de nuestra
                comunidad</p>
            <a href="#servicios" class="hero-button" data-testid="hero-cta-button">Conocer Servicios</a>
        </div>
    </section>

    ---

    <section id="nosotros" class="section-light" data-testid="nosotros-section">
        <div class="container">
            <div class="observe-section">
                <h2 class="section-title" data-testid="nosotros-title">Sobre Nosotros</h2>
                <div class="section-divider"></div>
                <div class="grid-2">
                    <div class="content-card">
                        <div class="icon-wrapper">
                            <i class="bx bxs-landmark"></i>
                        </div>
                        <h3 data-testid="mision-title">Misión</h3>
                        <p data-testid="mision-text">
                            Promover el desarrollo integral del municipio Bruzual a través de políticas públicas
                            inclusivas, transparentes y eficientes que mejoren la calidad de vida de todos los
                            ciudadanos.
                        </p>
                    </div>
                    <div class="content-card">
                        <div class="icon-wrapper">
                            <i class="bx bx-show"></i>
                        </div>
                        <h3 data-testid="vision-title">Visión</h3>
                        <p data-testid="vision-text">
                            Ser reconocidos como un consejo municipal modelo en gestión pública, innovación y
                            participación ciudadana, consolidando un municipio próspero y sostenible.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    ---

    <section id="servicios" class="section-dark" data-testid="servicios-section">
        <div class="container">
            <div class="observe-section">
                <h2 class="section-title text-white" data-testid="servicios-title">Nuestros Servicios</h2>
                <div class="section-divider"></div>
                <div class="grid-3">
                    <div class="service-card" data-testid="service-card-1">
                        <div class="service-icon">
                            <i class="bx bx-file"></i>
                        </div>
                        <h3>Trámites y Permisos</h3>
                        <p>Gestión de documentos oficiales, permisos de construcción y certificaciones municipales.</p>
                    </div>
                    <div class="service-card" data-testid="service-card-2">
                        <div class="service-icon">
                            <i class="bx bxs-group"></i>
                        </div>
                        <h3>Atención Ciudadana</h3>
                        <p>Oficina de atención al público para consultas, reclamos y sugerencias de la comunidad.</p>
                    </div>
                    <div class="service-card" data-testid="service-card-5">
                        <div class="service-icon">
                            <i class="bx bxs-heart-circle"></i>
                        </div>
                        <h3>Bienestar Social</h3>
                        <p>Programas de asistencia social y apoyo a grupos vulnerables.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="proyectos" class="section-light" data-testid="proyectos-section">

        <div class="container">

            <div class="observe-section">

                <h2 class="section-title" data-testid="proyectos-title">Ubicanos en</h2>

                <div class="section-divider"></div>

                <div id="mapa-bruzual" style="height: 500px; width: 100%;"></div>

            </div>

        </div>

    </section>

    ---

    <section id="organizacion" class="section-dark" data-testid="organizacion-section">
        <div class="container">
            <div class="observe-section">
                <h2 class="section-title text-white" data-testid="organizacion-title">Estructura Organizacional</h2>
                <div class="section-divider"></div>
                <div class="grid-3">
                    <div class="team-card" data-testid="team-card-1">
                        <div class="team-avatar">
                            <i class="bx bxs-user-detail"></i>
                        </div>
                        <h3>Alcaldía</h3>
                        <p class="role">Poder Ejecutivo Municipal</p>
                        <p class="description">Encargado de la administración y ejecución de políticas municipales.</p>
                    </div>
                    <div class="team-card" data-testid="team-card-2">
                        <div class="team-avatar">
                            <i class='bx bx-building-house'></i>
                        </div>
                        <h3>Concejo Municipal</h3>
                        <p class="role">Poder Legislativo Local</p>
                        <p class="description">Órgano deliberante responsable de ordenanzas y normativas municipales.
                        </p>
                    </div>
                    <div class="team-card" data-testid="team-card-3">
                        <div class="team-avatar">
                            <i class="bx bx-check-shield"></i>
                        </div>
                        <h3>Contraloría Municipal</h3>
                        <p class="role">Control y Fiscalización</p>
                        <p class="description">Supervisa el uso correcto de recursos y cumplimiento normativo.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    ---

    <section id="contacto" class="section-light" data-testid="contacto-section">
        <div class="container">
            <div class="observe-section">
                <h2 class="section-title" data-testid="contacto-title">Nuestro sistema de atencion al ciudadano</h2>
                <div class="section-divider"></div>
                <div class="contact-grid">

                </div>
            </div>
        </div>
    </section>

    <footer class="footer" data-testid="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Consejo Municipal de Bruzual</h3>
                    <p>Comprometidos con el desarrollo y bienestar de nuestra comunidad.</p>
                </div>
                <div class="footer-section">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="#nosotros" data-testid="footer-link-nosotros">Nosotros</a></li>
                        <li><a href="#servicios" data-testid="footer-link-servicios">Servicios</a></li>
                        <li><a href="#proyectos" data-testid="footer-link-proyectos">Proyectos</a></li>
                        <li><a href="#contacto" data-testid="footer-link-contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Síguenos</h4>
                    <div class="social-links">
                        <a href="#" data-testid="social-facebook" aria-label="Facebook">
                            <i class="bx bxl-facebook-square"></i>
                        </a>
                        <a href="#" data-testid="social-twitter" aria-label="Twitter">
                            <i class="bx bxl-twitter"></i>
                        </a>
                        <a href="#" data-testid="social-instagram" aria-label="Instagram">
                            <i class="bx bxl-instagram-alt"></i>
                        </a>
                        <a href="#" data-testid="social-youtube" aria-label="YouTube">
                            <i class="bx bxl-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Consejo Municipal de Bruzual. Todos los derechos reservados a pepe</p>
            </div>
        </div>
    </footer>

</body>

</html>

<script>
    // ----------------------------------------------------------------------------------
    // 1. Lógica del Mapa (Mapkick)
    // ----------------------------------------------------------------------------------
    document.addEventListener('DOMContentLoaded', function() {
        // Coordenadas aproximadas para Chivacoa (Capital del Municipio Bruzual)
        const bruzualLocation = { 
            latitude: 10.1500, // Latitud de Chivacoa
            longitude: -68.9000, // Longitud de Chivacoa
            label: "Consejo Municipal de Bruzual (Chivacoa)" 
        };

        // Inicializar el mapa de Mapkick en el elemento 'mapa-bruzual'
        new Mapkick.Map("mapa-bruzual", {
            // El mapa se centrará y mostrará este punto
            data: [bruzualLocation], 
            // Opcional: Establecer un centro y zoom específicos si el punto 'data' no es suficiente
            center: [bruzualLocation.latitude, bruzualLocation.longitude],
            zoom: 12 
        });
    });

    // ----------------------------------------------------------------------------------
    // 2. Lógica de la Navegación y Animaciones (Ya existente)
    // ----------------------------------------------------------------------------------

    let lastScrollY = window.scrollY;
    const navbar = document.getElementById('navbar');

    function handleNavbarScroll() {
        const currentScrollY = window.scrollY;
        
        if (currentScrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        
        lastScrollY = currentScrollY;
    }

    window.addEventListener('scroll', handleNavbarScroll);

    // Smooth scroll for navigation links
    const navLinks = document.querySelectorAll('.nav-link');
    const footerLinks = document.querySelectorAll('.footer-section ul a');
    const heroButton = document.querySelector('.hero-button');

    function smoothScrollToSection(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        const targetSection = document.querySelector(targetId);
        
        if (targetSection) {
            const offset = 80;
            const elementPosition = targetSection.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - offset;
            
            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    }

    navLinks.forEach(link => {
        link.addEventListener('click', smoothScrollToSection);
    });

    footerLinks.forEach(link => {
        link.addEventListener('click', smoothScrollToSection);
    });

    if (heroButton) {
        heroButton.addEventListener('click', smoothScrollToSection);
    }

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    const sections = document.querySelectorAll('.observe-section');
    sections.forEach(section => {
        observer.observe(section);
    });


    window.addEventListener('load', function() {
        setTimeout(() => {
            const heroContent = document.querySelector('.hero-content');
            if (heroContent) {
                heroContent.classList.add('animate-in');
            }
        }, 200);
    });
</script>