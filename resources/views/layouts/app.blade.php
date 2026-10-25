<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Inicio') · {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bs-body-font-family: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;
            --verde: #0B5D4B;
            --verde-oscuro: #084838;
            --amarillo: #F4C430;
            --tinta: #14211C;
            --fondo: #F3F6F2;
            --borde: #DFE6DC;
        }
        body { background: var(--fondo); color: var(--tinta); min-height: 100vh; display: flex; flex-direction: column; }
        main { flex: 1; }
        a { color: var(--verde); }
        .barra { background: var(--verde); }
        .barra .navbar-brand, .barra .nav-link, .barra .btn-link { color: #fff; }
        .barra .nav-link:hover, .barra .nav-link.active { color: var(--amarillo); }
        .marca { font-weight: 800; letter-spacing: -.02em; }
        .marca span { color: var(--amarillo); }
        .btn-primary {
            --bs-btn-bg: var(--verde); --bs-btn-border-color: var(--verde);
            --bs-btn-hover-bg: var(--verde-oscuro); --bs-btn-hover-border-color: var(--verde-oscuro);
            --bs-btn-active-bg: var(--verde-oscuro); --bs-btn-active-border-color: var(--verde-oscuro);
            --bs-btn-disabled-bg: var(--verde); --bs-btn-disabled-border-color: var(--verde);
        }
        .btn-cupon { background: var(--amarillo); border-color: var(--amarillo); color: var(--tinta); font-weight: 600; }
        .btn-cupon:hover { background: #e0b020; border-color: #e0b020; color: var(--tinta); }
        .form-control:focus, .form-select:focus { border-color: var(--verde); box-shadow: 0 0 0 .2rem rgba(11, 93, 75, .2); }

        /* Tarjeta con forma de cupón */
        .ticket { background: #fff; border: 1px solid var(--borde); border-radius: .75rem; overflow: hidden; display: flex; flex-direction: column; height: 100%; }
        .ticket-corte { border-top: 2px dashed #c4cfc0; margin: 0 1.25rem; position: relative; }
        .ticket-corte::before, .ticket-corte::after {
            content: ""; position: absolute; top: -11px; width: 20px; height: 20px;
            border-radius: 50%; background: var(--fondo); border: 1px solid var(--borde);
        }
        .ticket-corte::before { left: calc(-1.25rem - 10px); }
        .ticket-corte::after { right: calc(-1.25rem - 10px); }
        .precio { font-size: 1.75rem; font-weight: 800; color: var(--verde); line-height: 1; }

        .panel { background: #fff; border: 1px solid var(--borde); border-radius: .75rem; }
        .cifra { font-size: 2.25rem; font-weight: 800; line-height: 1; }
        footer { color: #5d6b64; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg barra navbar-dark">
    <div class="container">
        <a class="navbar-brand marca" href="{{ route('home') }}">La Cuponera <span>SV</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"
                aria-controls="menu" aria-expanded="false" aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Cupones</a></li>
                @auth
                    @if(auth()->user()->rol === 'admin')
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Panel</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.empresas.*') ? 'active' : '' }}" href="{{ route('admin.empresas.index') }}">Empresas</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.administradores.*') ? 'active' : '' }}" href="{{ route('admin.administradores.index') }}">Administradores</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route(auth()->user()->dashboardRoute()) }}">Mi cuenta</a></li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav align-items-lg-center gap-lg-2">
                @guest
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Ingresar</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('login') }}">Soy cliente</a></li>
                            <li><a class="dropdown-item" href="{{ route('empresa.login') }}">Soy una empresa</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.login') }}">Administrador</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="btn btn-cupon dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Registrarme</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('cliente.registro') }}">Como cliente</a></li>
                            <li><a class="dropdown-item" href="{{ route('empresa.registro') }}">Como empresa</a></li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item text-white small me-lg-2">{{ auth()->user()->nombreParaMostrar() }}</li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Cerrar sesión</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4 py-lg-5">
    @if(session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif

    @yield('contenido')
</main>

<footer class="container py-4 small border-top">
    La Cuponera SV · Proyecto de cátedra · Avance de la Fase 1
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
