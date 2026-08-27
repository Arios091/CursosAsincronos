@extends('layouts.guest')

@section('content')
@php
    $settings = \App\Models\PageSetting::getAll();
    $primaryColor = $settings['primary_color'] ?? '#0B5E2E';
    $secondaryColor = $settings['secondary_color'] ?? '#C9A227';
    $heroTitle = $settings['hero_title'] ?? 'Sistema de <span>Gestion de Docencia</span> UNAS';
    $heroSubtitle = $settings['hero_subtitle'] ?? 'Plataforma oficial de educacion continua de la Universidad Nacional Agraria de la Selva';
    $contactPhone = $settings['contact_phone'] ?? '(062) 562341';
    $contactEmail = $settings['contact_email'] ?? 'mesadepartes@unas.edu.pe';
    $contactAddress = $settings['contact_address'] ?? 'Carretera Central Km. 1.21, Tingo Maria';
    $institutionName = $settings['institution_name'] ?? 'Universidad Nacional Agraria de la Selva';
    $institutionAcronym = $settings['institution_acronym'] ?? 'UNAS';
    $footerText = $settings['footer_text'] ?? '© ' . date('Y') . ' Universidad Nacional Agraria de la Selva. Todos los derechos reservados.';
    $socialFacebook = $settings['social_facebook'] ?? '';
    $socialTwitter = $settings['social_twitter'] ?? '';
    $socialInstagram = $settings['social_instagram'] ?? '';
    $logo = isset($settings['logo']) ? storage_url($settings['logo']) : null;
    $loginBg = isset($settings['login_bg']) ? storage_url($settings['login_bg']) : null;
@endphp

<style>
    :root {
        --primary: {{ $primaryColor }};
        --secondary: {{ $secondaryColor }};
    }
    .bg-primary-custom { background-color: {{ $primaryColor }}; }
    .bg-secondary-custom { background-color: {{ $secondaryColor }}; }
    .text-secondary-custom { color: {{ $secondaryColor }}; }
    .btn-primary-custom {
        background-color: {{ $primaryColor }};
        border-color: {{ $primaryColor }};
        color: #fff;
    }
    .btn-primary-custom:hover {
        background-color: #0A4A24;
        border-color: #0A4A24;
        color: #fff;
    }
    .btn-outline-custom {
        border: 2px solid #fff;
        color: #fff;
        background: transparent;
    }
    .btn-outline-custom:hover {
        background: #fff;
        color: {{ $primaryColor }};
    }

    /* Top bar */
    .top-bar {
        background: {{ $primaryColor }};
        color: #fff;
        font-size: 0.85rem;
        padding: 6px 0;
    }
    .top-bar a { color: #fff; text-decoration: none; }

    /* Navbar */
    .guest-navbar {
        background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    /* Hero */
    .hero-section {
        background: linear-gradient(135deg, {{ $primaryColor }} 0%, #0A4A24 50%, #063818 100%);
        min-height: 85vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .hero-title { font-size: 2.8rem; font-weight: 800; line-height: 1.2; }
    .hero-title span { color: {{ $secondaryColor }}; }
    .hero-subtitle { font-size: 1.15rem; opacity: 0.9; line-height: 1.6; }

    /* Carousel */
    .carousel-item { height: 280px; background: rgba(255,255,255,0.1); border-radius: 12px; overflow: hidden; }
    .carousel-item img { width: 100%; height: 100%; object-fit: cover; }
    .carousel-indicators-custom { position: absolute; bottom: -35px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; }
    .carousel-indicators-custom button {
        width: 12px; height: 12px; border-radius: 50%; border: 2px solid {{ $secondaryColor }};
        background: transparent; cursor: pointer; padding: 0; transition: all 0.3s;
    }
    .carousel-indicators-custom button.active { background: {{ $secondaryColor }}; }

    /* Features */
    .feature-card {
        border: none; border-radius: 12px; transition: all 0.3s; padding: 2rem 1.5rem;
    }
    .feature-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .feature-icon {
        width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center;
        justify-content: center; margin: 0 auto 1rem; font-size: 1.8rem; color: #fff;
    }

    /* Stats */
    .stats-section { background: {{ $primaryColor }}; color: #fff; padding: 3rem 0; }
    .stat-number { font-size: 2.5rem; font-weight: 800; }
    .stat-label { opacity: 0.85; font-size: 1rem; }

    /* Footer */
    .footer { background: #1a1a2e; color: #ccc; padding: 2.5rem 0 1rem; }
    .footer a { color: {{ $secondaryColor }}; }

    @media (max-width: 768px) {
        .hero-title { font-size: 1.8rem; }
        .hero-section { min-height: 60vh; }
    }
</style>

<!-- Top Bar -->
<div class="top-bar text-center text-md-left">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <i class="fas fa-phone mr-1"></i> {{ $contactPhone }}
                <span class="mx-2">|</span>
                <i class="fas fa-envelope mr-1"></i> <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
            </div>
            <div class="col-md-6 text-md-right mt-1 mt-md-0">
                <small>{{ $institutionName }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg guest-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/">
            @if($logo)
                <img src="{{ $logo }}" alt="UNAS" height="40" class="mr-2">
            @else
                <span class="font-weight-bold" style="color: {{ $primaryColor }};">{{ $institutionAcronym }}</span>
            @endif
            <span class="ml-2 font-weight-bold" style="color: {{ $primaryColor }};">Cursos SGD</span>
        </a>
        <div class="ml-auto d-flex align-items-center">
            <a href="{{ route('login') }}" class="btn btn-outline-primary-custom mr-2" style="border-color: {{ $primaryColor }}; color: {{ $primaryColor }};">Iniciar Sesion</a>
            <a href="{{ route('register') }}" class="btn btn-primary-custom">Crear Cuenta</a>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero-section">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white mb-4 mb-lg-0">
                <h1 class="hero-title">{!! $heroTitle !!}</h1>
                <p class="hero-subtitle mt-3">{{ $heroSubtitle }}</p>
                <div class="mt-4">
                    <a href="{{ route('register') }}" class="btn btn-secondary-custom text-white font-weight-bold px-4 py-2 mr-2" style="background: {{ $secondaryColor }}; border: none;">
                        Comenzar Ahora
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-custom font-weight-bold px-4 py-2">Iniciar Sesion</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div id="heroCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @php $carouselActive = true; @endphp
                        @for($i = 1; $i <= 4; $i++)
                            @php $carouselKey = 'carousel_' . $i; @endphp
                            <div class="carousel-item {{ $carouselActive ? 'active' : '' }}">
                                @if(isset($settings[$carouselKey]) && $settings[$carouselKey])
                                    <img src="{{ storage_url($settings[$carouselKey]) }}" alt="Slide {{ $i }}">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 text-white">
                                        <div class="text-center">
                                            <i class="fas fa-university fa-4x mb-3" style="color: {{ $secondaryColor }};"></i>
                                            <p>Bienvenido a la UNAS</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @php $carouselActive = false; @endphp
                        @endfor
                    </div>
                    <div class="carousel-indicators-custom" style="position: absolute; bottom: -35px; left: 0; right: 0; display: flex; justify-content: center; gap: 8px;">
                        @for($i = 0; $i < 4; $i++)
                            <button data-target="#heroCarousel" data-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"></button>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="font-weight-bold" style="color: {{ $primaryColor }};">Nuestra Plataforma</h2>
            <p class="text-muted">Todo lo que necesitas para tu formacion continua</p>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="feature-card text-center card h-100 shadow-sm">
                    <div class="feature-icon" style="background: {{ $primaryColor }};">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h5 class="font-weight-bold">Aprendizaje Asincrono</h5>
                    <p class="text-muted">Accede a los cursos en cualquier momento y desde cualquier lugar, a tu propio ritmo.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card text-center card h-100 shadow-sm">
                    <div class="feature-icon" style="background: {{ $secondaryColor }};">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h5 class="font-weight-bold">Certificacion Oficial</h5>
                    <p class="text-muted">Obtiene certificados avalados por la Universidad Nacional Agraria de la Selva.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card text-center card h-100 shadow-sm">
                    <div class="feature-icon" style="background: {{ $primaryColor }};">
                        <i class="fas fa-book"></i>
                    </div>
                    <h5 class="font-weight-bold">Recursos Digitales</h5>
                    <p class="text-muted">Videos, PDFs y cuestionarios interactivos para un aprendizaje completo.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="stats-section text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="stat-number">+500</div>
                <div class="stat-label">Estudiantes Capacitados</div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="stat-number">+50</div>
                <div class="stat-label">Cursos Disponibles</div>
            </div>
            <div class="col-md-4">
                <div class="stat-number">100%</div>
                <div class="stat-label">Online</div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="text-white">SGD - {{ $institutionAcronym }}</h5>
                <p class="small mt-2">Sistema de Gestion de Docencia de la {{ $institutionName }}.</p>
                @if($socialFacebook || $socialTwitter || $socialInstagram)
                <div class="mt-3">
                    @if($socialFacebook) <a href="{{ $socialFacebook }}" target="_blank" class="text-white mr-2" title="Facebook"><i class="fab fa-facebook fa-lg"></i></a> @endif
                    @if($socialTwitter) <a href="{{ $socialTwitter }}" target="_blank" class="text-white mr-2" title="Twitter"><i class="fab fa-twitter fa-lg"></i></a> @endif
                    @if($socialInstagram) <a href="{{ $socialInstagram }}" target="_blank" class="text-white" title="Instagram"><i class="fab fa-instagram fa-lg"></i></a> @endif
                </div>
                @endif
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="text-white">Contacto</h5>
                <p class="small mt-2 mb-1"><i class="fas fa-phone mr-1"></i> {{ $contactPhone }}</p>
                <p class="small mb-1"><i class="fas fa-envelope mr-1"></i> <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></p>
                <p class="small mb-1"><i class="fas fa-map-marker-alt mr-1"></i> {{ $contactAddress }}</p>
            </div>
            <div class="col-md-4">
                <h5 class="text-white">Enlaces</h5>
                <ul class="list-unstyled small mt-2">
                    <li><a href="{{ route('login') }}">Iniciar Sesion</a></li>
                    <li><a href="{{ route('register') }}">Crear Cuenta</a></li>
                    <li><a href="https://www.unas.edu.pe" target="_blank">Portal UNAS</a></li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary my-3">
        <p class="text-center small mb-0">{{ $footerText }}</p>
    </div>
</footer>
@endsection
