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
            --primario: #4338CA;
            --primario-osc: #3730A3;
            --primario-claro: #EEF0FF;
            --violeta: #7C3AED;
            --acento: #FF6B4A;
            --acento-osc: #F0512F;
            --tinta: #1E1B3A;
            --fondo: #F6F5FB;
            --borde: #E4E2F0;
            --radio: 1rem;
            --sombra: 0 .25rem 1rem rgba(30, 27, 58, .06);
            --bs-link-color: var(--primario);
            --bs-link-hover-color: var(--primario-osc);
            --bs-pagination-active-bg: var(--primario);
            --bs-pagination-active-border-color: var(--primario);
            --bs-pagination-color: var(--primario);
        }
        body { background: var(--fondo); color: var(--tinta); min-height: 100vh; display: flex; flex-direction: column; }
        main { flex: 1; }
        h1, h2, h3, h4, h5 { letter-spacing: -.02em; }

        /* Barra de navegación clara */
        .barra { background: #fff; border-bottom: 1px solid var(--borde); }
        .barra .navbar-brand { color: var(--primario); }
        .barra .nav-link { color: var(--tinta); font-weight: 600; border-radius: .5rem; padding: .45rem .8rem; }
        .barra .nav-link:hover { background: var(--primario-claro); color: var(--primario); }
        .barra .nav-link.active { background: var(--primario-claro); color: var(--primario); }
        .marca { font-weight: 800; letter-spacing: -.03em; font-size: 1.35rem; }
        .marca span { color: var(--acento); }

        /* Botones */
        .btn { border-radius: .65rem; font-weight: 600; }
        .btn-primary {
            --bs-btn-bg: var(--primario); --bs-btn-border-color: var(--primario);
            --bs-btn-hover-bg: var(--primario-osc); --bs-btn-hover-border-color: var(--primario-osc);
            --bs-btn-active-bg: var(--primario-osc); --bs-btn-active-border-color: var(--primario-osc);
            --bs-btn-disabled-bg: var(--primario); --bs-btn-disabled-border-color: var(--primario);
        }
        .btn-outline-secondary { --bs-btn-color: var(--tinta); --bs-btn-border-color: var(--borde); --bs-btn-hover-bg: var(--primario-claro); --bs-btn-hover-color: var(--primario); --bs-btn-hover-border-color: var(--primario); --bs-btn-active-bg: var(--primario-claro); --bs-btn-active-color: var(--primario); }
        .btn-cupon { background: var(--acento); border-color: var(--acento); color: #fff; font-weight: 700; box-shadow: 0 .3rem .8rem rgba(255, 107, 74, .3); }
        .btn-cupon:hover, .btn-cupon:focus { background: var(--acento-osc); border-color: var(--acento-osc); color: #fff; }
        .form-control, .form-select { border-radius: .65rem; border-color: #d5d2e6; padding-top: .55rem; padding-bottom: .55rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primario); box-shadow: 0 0 0 .25rem rgba(67, 56, 202, .18); }

        /* Tarjeta con forma de cupón */
        .ticket { background: #fff; border: 1px solid var(--borde); border-radius: var(--radio); overflow: hidden; display: flex; flex-direction: column; height: 100%; box-shadow: var(--sombra); }
        .ticket-corte { border-top: 2px dashed #cfcbe6; margin: 0 1.25rem; position: relative; }
        .ticket-corte::before, .ticket-corte::after {
            content: ""; position: absolute; top: -11px; width: 20px; height: 20px;
            border-radius: 50%; background: var(--fondo); border: 1px solid var(--borde);
        }
        .ticket-corte::before { left: calc(-1.25rem - 10px); }
        .ticket-corte::after { right: calc(-1.25rem - 10px); }
        .precio { font-size: 1.85rem; font-weight: 800; color: var(--primario); line-height: 1; }

        .panel { background: #fff; border: 1px solid var(--borde); border-radius: var(--radio); box-shadow: var(--sombra); }
        a.panel { transition: transform .15s ease, border-color .15s ease; }
        a.panel:hover { transform: translateY(-2px); border-color: var(--primario); }
        .cifra { font-size: 2.25rem; font-weight: 800; line-height: 1; letter-spacing: -.03em; }
        .table { --bs-table-bg: transparent; }
        .table thead th { font-size: .78rem; text-transform: uppercase; letter-spacing: .05em; color: #6b6890; border-bottom-color: var(--borde); }
        footer { color: #6b6890; }

        .hero { position: relative; overflow: hidden; background: linear-gradient(120deg, var(--primario) 0%, var(--violeta) 100%); color: #fff; border-radius: 1.25rem; padding: 2.25rem 2rem; }
        .hero::before, .hero::after { content: ""; position: absolute; border-radius: 50%; pointer-events: none; }
        .hero::before { width: 220px; height: 220px; right: -60px; top: -80px; background: rgba(255, 255, 255, .12); }
        .hero::after { width: 120px; height: 120px; right: 90px; bottom: -60px; background: var(--acento); opacity: .85; }
        .hero > * { position: relative; z-index: 1; }
        .text-resalte { color: #FFD3C8; }
        .ticket-hover { transition: transform .15s ease, box-shadow .15s ease; }
        .ticket-hover:hover { transform: translateY(-4px); box-shadow: 0 .75rem 1.5rem rgba(67, 56, 202, .16); }
        .barra-datos { height: .6rem; border-radius: 1rem; background: var(--primario-claro); overflow: hidden; }
        .barra-datos > span { display: block; height: 100%; background: linear-gradient(90deg, var(--primario), var(--violeta)); border-radius: 1rem; }
        .pestana-activa { background: var(--primario) !important; color: #fff !important; }
        .btn:focus-visible, a:focus-visible { outline: 3px solid var(--acento); outline-offset: 2px; }
        @media (prefers-reduced-motion: reduce) { .ticket-hover, a.panel { transition: none; } .ticket-hover:hover, a.panel:hover { transform: none; } }
    </style>
    @stack('estilos')
</head>
<body>
<nav class="navbar navbar-expand-lg barra">
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
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.reportes') ? 'active' : '' }}" href="{{ route('admin.reportes') }}">Reportes</a></li>
                    @elseif(auth()->user()->rol === 'empresa')
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('empresa.dashboard') ? 'active' : '' }}" href="{{ route('empresa.dashboard') }}">Mi empresa</a></li>
                        @if(auth()->user()->empresa?->estaAprobada())
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('empresa.ofertas.*') ? 'active' : '' }}" href="{{ route('empresa.ofertas.index') }}">Mis ofertas</a></li>
                        @endif
                    @else
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('cliente.dashboard') ? 'active' : '' }}" href="{{ route('cliente.dashboard') }}">Mi cuenta</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('compras.*') ? 'active' : '' }}" href="{{ route('compras.index') }}">Mis cupones</a></li>
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
                    <li class="nav-item small fw-semibold me-lg-2">{{ auth()->user()->nombreParaMostrar() }}</li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">Cerrar sesión</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4 py-lg-5">
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif
    @yield('contenido')
</main>

<footer class="container py-4 small border-top">
    La Cuponera SV · Proyecto de cátedra · LIS941 · Fase 2
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Evita envíos dobles: al enviar un formulario POST se deshabilita su botón principal.
    document.querySelectorAll('form[method="POST"]').forEach(function (form) {
        form.addEventListener('submit', function () {
            const boton = form.querySelector('button[type="submit"]');
            if (!boton || boton.dataset.sinBloqueo !== undefined) return;
            setTimeout(function () {
                boton.disabled = true;
                boton.dataset.texto = boton.textContent;
                boton.textContent = 'Procesando…';
            }, 0);
        });
    });
    // Al volver con el botón "atrás" los botones no deben quedar bloqueados.
    window.addEventListener('pageshow', function (e) { if (e.persisted) location.reload(); });
    // Si el formulario vuelve con errores, lleva el foco al primer campo inválido.
    const invalido = document.querySelector('.is-invalid');
    if (invalido) { invalido.scrollIntoView({ block: 'center' }); invalido.focus({ preventScroll: true }); }
    // Botones "Copiar" (data-copiar="texto").
    document.querySelectorAll('[data-copiar]').forEach(function (boton) {
        boton.addEventListener('click', function () {
            navigator.clipboard.writeText(boton.dataset.copiar).then(function () {
                const original = boton.textContent;
                boton.textContent = '¡Copiado!';
                setTimeout(function () { boton.textContent = original; }, 1500);
            });
        });
    });
</script>
@stack('scripts')
</body>
</html>
