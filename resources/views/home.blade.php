@extends('layouts.app')

@section('titulo', 'Cupones disponibles')

@section('contenido')
    <section class="hero mb-4">
        <h1 class="fw-bold mb-1">Cupones con <span class="text-resalte">descuentos que sí se notan</span></h1>
        <p class="mb-0 opacity-75">Compra en línea y canjea en las empresas aliadas de El Salvador.</p>
    </section>

    <form method="GET" action="{{ route('home') }}" class="row g-2 mb-4 align-items-center" role="search">
        <div class="col-12 col-md">
            <label for="q" class="visually-hidden">Buscar cupones</label>
            <input type="search" id="q" name="q" value="{{ $q }}" maxlength="80"
                   class="form-control" placeholder="Buscar por oferta, descripción o empresa…">
        </div>
        <div class="col-8 col-md-auto">
            <label for="orden" class="visually-hidden">Ordenar por</label>
            <select id="orden" name="orden" class="form-select" onchange="this.form.submit()">
                @foreach($ordenes as $clave => $texto)
                    <option value="{{ $clave }}" @selected($orden === $clave)>{{ $texto }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-4 col-md-auto d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">Buscar</button>
            @if($q !== '' || $orden !== 'recientes')
                <a href="{{ route('home') }}" class="btn btn-outline-secondary" title="Quitar filtros">&times;</a>
            @endif
        </div>
    </form>

    @if($ofertas->isEmpty())
        <div class="panel p-5 text-center">
            @if($q !== '')
                <p class="mb-1 fw-semibold">No encontramos cupones para “{{ $q }}”.</p>
                <p class="text-secondary">Prueba con otra palabra o quita los filtros.</p>
                <a href="{{ route('home') }}" class="btn btn-cupon">Ver todos los cupones</a>
            @else
                <p class="mb-1 fw-semibold">Todavía no hay ofertas disponibles.</p>
                <p class="text-secondary mb-0">Cuando las empresas publiquen sus cupones, los verás aquí.</p>
            @endif
        </div>
    @else
        <p class="small text-secondary" aria-live="polite">
            {{ $ofertas->total() }} {{ $ofertas->total() === 1 ? 'cupón' : 'cupones' }}
            @if($q !== '') para “{{ $q }}” @endif
        </p>

        <div class="row g-4">
            @foreach($ofertas as $oferta)
                @php
                    $dias = (int) today()->diffInDays($oferta->fecha_fin, false);
                    $restantes = $oferta->cuponesRestantes();
                @endphp
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="ticket ticket-hover">
                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="small text-secondary">{{ $oferta->empresa->nombre }}</div>
                                <span class="badge text-bg-warning">-{{ $oferta->porcentajeDescuento() }}%</span>
                            </div>
                            <h2 class="h5 fw-bold my-2">{{ $oferta->titulo }}</h2>
                            <p class="small mb-0">{{ \Illuminate\Support\Str::limit($oferta->descripcion, 110) }}</p>
                        </div>
                        <div class="ticket-corte"></div>
                        <div class="p-3 pb-2">
                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <div class="text-decoration-line-through text-secondary small">${{ number_format($oferta->precio_regular, 2) }}</div>
                                    <div class="precio">${{ number_format($oferta->precio_oferta, 2) }}</div>
                                </div>
                                <div class="small text-success fw-semibold">
                                    Ahorras ${{ number_format($oferta->precio_regular - $oferta->precio_oferta, 2) }}
                                </div>
                            </div>
                        </div>
                        <div class="px-3 pb-2 small d-flex flex-wrap gap-2">
                            @if($dias <= 3)
                                <span class="badge text-bg-danger">{{ $dias <= 0 ? 'Último día' : 'Termina en '.$dias.' '.($dias === 1 ? 'día' : 'días') }}</span>
                            @else
                                <span class="text-secondary">Hasta el {{ $oferta->fecha_fin->format('d/m/Y') }}</span>
                            @endif
                            @if($restantes === 0)
                                <span class="badge text-bg-secondary">Agotado</span>
                            @elseif($restantes !== null && $restantes <= 10)
                                <span class="badge text-bg-warning">¡Quedan {{ $restantes }}!</span>
                            @endif
                        </div>
                        <div class="px-3 pb-3 mt-auto">
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-cupon w-100">Inicia sesión para comprar</a>
                            @else
                                @if(auth()->user()->rol === 'cliente')
                                    @if($oferta->agotada())
                                        <button type="button" class="btn btn-outline-secondary w-100" disabled>Agotado</button>
                                    @else
                                        <a href="{{ route('compras.create', $oferta->id) }}" class="btn btn-primary w-100">Comprar cupón</a>
                                    @endif
                                @else
                                    <button type="button" class="btn btn-outline-secondary w-100" disabled>Solo los clientes pueden comprar</button>
                                @endif
                            @endguest
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $ofertas->links('pagination::bootstrap-5') }}</div>
    @endif
@endsection
