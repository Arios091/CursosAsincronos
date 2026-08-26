<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'SGD - UNAS'))</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @livewireStyles
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-green: #0B5E2E;
            --secondary-gold: #C9A227;
            --sidebar-bg: #0A4A24;
            --sidebar-hover: #0D6B35;
            --bottom-nav-height: 62px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: #f4f6f9;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: #fff;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }
        .sidebar-brand {
            padding: 1.25rem 1.25rem 0.75rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand img, .sidebar-brand svg {
            max-width: 48px;
            margin-bottom: 0.25rem;
        }
        .sidebar-brand span {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }
        .sidebar-user {
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .avatar-initials {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--secondary-gold);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .sidebar-user-info {
            overflow: hidden;
        }
        .sidebar-user-info .name {
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-user-info .role {
            font-size: 0.75rem;
            opacity: 0.7;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 0.75rem 0;
        }
        .sidebar-nav .nav-section {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.5;
            padding: 1rem 1.25rem 0.35rem;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.25rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: var(--sidebar-hover);
            color: #fff;
        }
        .sidebar-nav a i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar-footer {
            padding: 0.75rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.9rem;
            padding: 0.4rem 0;
            transition: color 0.2s;
        }
        .sidebar-footer a:hover { color: #fff; }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 0;
            transition: margin-left 0.3s ease;
            padding-bottom: 0;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--bottom-nav-height);
            background: #fff;
            border-top: 1px solid #e0e0e0;
            display: none;
            z-index: 1030;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.08);
        }
        .bottom-nav-inner {
            display: flex;
            height: 100%;
            align-items: stretch;
        }
        .bottom-nav a {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.6rem;
            transition: color 0.2s;
            padding: 2px 0;
        }
        .bottom-nav a i {
            font-size: 1.25rem;
            margin-bottom: 1px;
        }
        .bottom-nav a:hover, .bottom-nav a.active {
            color: var(--primary-green);
        }
        .bottom-nav .avatar-xs {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--primary-green);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 700;
            margin-bottom: 1px;
        }

        .alerts-container {
            padding: 1rem 1.5rem 0;
            max-width: 100%;
        }
        .alerts-container .alert { margin-bottom: 0.5rem; }

        .content-area {
            padding: 1.5rem;
        }

        @media (max-width: 767.98px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
            .bottom-nav { display: block; }
            .content-area { padding: 1rem 0.75rem 5rem; }
            .alerts-container { padding: 0.75rem 0.75rem 0; }
        }
    </style>
</head>
<body>
    @auth
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/unas-logo.png') }}" alt="UNAS" style="max-width:42px;margin-bottom:0.25rem;">
            <span>SGD - UNAS</span>
        </div>

        <div class="sidebar-user">
            <div class="avatar-initials">{{ initials(auth()->user()->name) }}</div>
            <div class="sidebar-user-info">
                <div class="name">{{ auth()->user()->name }}</div>
                <div class="role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Navegacion</div>
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Inicio
            </a>
            <a href="{{ route('cursos.index') }}" class="{{ request()->routeIs('cursos.index') ? 'active' : '' }}">
                <i class="fas fa-book"></i> Cursos
            </a>
            <a href="{{ route('cursos.index') }}?mis-cursos=1" class="{{ request()->routeIs('mis-cursos*') ? 'active' : '' }}">
                <i class="fas fa-graduation-cap"></i> Mis Cursos
            </a>
            <a href="{{ route('cursos.index') }}?certificados=1" class="{{ request()->routeIs('certificado.*') ? 'active' : '' }}">
                <i class="fas fa-certificate"></i> Certificados
            </a>

            @if(auth()->user()->puedeGestionarCursos())
            <div class="nav-section">Gestion</div>
            <a href="{{ route('cursos.index', ['gestion' => 1]) }}" class="{{ request()->routeIs('cursos.index') && request()->has('gestion') ? 'active' : '' }}">
                <i class="fas fa-edit"></i> Gestionar Cursos
            </a>
            <a href="{{ route('crear.curso') }}" class="{{ request()->routeIs('crear.curso') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i> Crear Curso
            </a>
            @endif

            @if(auth()->user()->puedeGestionarUsuarios())
            <div class="nav-section">Administracion</div>
            <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i> Usuarios
            </a>
            <a href="{{ route('admin.page-settings') }}" class="{{ request()->routeIs('admin.page-settings') ? 'active' : '' }}">
                <i class="fas fa-palette"></i> Configuración
            </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesion
            </a>
            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="main-content" id="mainContent">
        <div class="alerts-container">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-2">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-2">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            @endif
        </div>

        <div class="content-area">
            @yield('content')
            {{ $slot ?? '' }}
        </div>
    </div>

    {{-- Bottom Nav (mobile) --}}
    <nav class="bottom-nav">
        <div class="bottom-nav-inner">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Inicio
            </a>
            <a href="{{ route('cursos.index') }}" class="{{ request()->routeIs('cursos.index') ? 'active' : '' }}">
                <i class="fas fa-book"></i> Cursos
            </a>
            <a href="{{ route('cursos.index') }}?mis-cursos=1" class="{{ request()->routeIs('mis-cursos*') ? 'active' : '' }}">
                <i class="fas fa-graduation-cap"></i> Mis Cursos
            </a>
            <a href="{{ route('cursos.index') }}?certificados=1" class="{{ request()->routeIs('certificado.*') ? 'active' : '' }}">
                <i class="fas fa-certificate"></i> Certificados
            </a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-bottom').submit();">
                <div class="avatar-xs">{{ initials(auth()->user()->name) }}</div>
                Salir
            </a>
            <form id="logout-form-bottom" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </nav>
    @endauth

    @guest
    <div class="content-area">
        @yield('content')
        {{ $slot ?? '' }}
    </div>
    @endguest

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireScripts
    <script>
        Livewire.hook('component.initialized', function (component) {});
        Livewire.hook('element.initialized', function (el, component) {});
        Livewire.hook('element.updating', function (fromEl, toEl, component) {});
        Livewire.hook('element.updated', function (el, component) {});
        Livewire.hook('element.removed', function (el, component) {});
        Livewire.hook('message.sent', function (message, component) {});
        Livewire.hook('message.failed', function (message, component) {});
        Livewire.hook('message.received', function (message, component) {});
        Livewire.hook('message.processed', function (message, component) {});
    </script>

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold"><i class="fas fa-exclamation-triangle text-warning mr-1"></i> Confirmar</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body pt-2" id="confirmModalMessage"></div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-danger" id="confirmModalBtn">Si, continuar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showConfirm(message, onConfirm) {
            document.getElementById('confirmModalMessage').textContent = message;
            document.getElementById('confirmModalBtn').onclick = function () {
                $('#confirmModal').modal('hide');
                onConfirm();
            };
            $('#confirmModal').modal('show');
        }
    </script>

    @stack('scripts')
</body>
</html>
