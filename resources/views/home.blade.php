@extends('layouts.app')

@section('titulo', 'Cupones disponibles')

@section('contenido')
    <div class="mb-4">
        <h1 class="fw-bold">Cupones disponibles</h1>
        <p class="text-secondary mb-0">Compra con descuento y canjea en las empresas aliadas.</p>
    </div>

    @if($ofertas->isEmpty())
        <div class="panel p-4 text-center">
            <p class="mb-1 fw-semibold">Todavía no hay ofertas disponibles.</p>
            <p class="text-secondary mb-0">Cuando las empresas publiquen sus cupones, los verás aquí.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($ofertas as $oferta)
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="ticket">
                        <div class="p-3">
                            <div class="small text-secondary">{{ $oferta->empresa->nombre }}</div>
                            <h2 class="h5 fw-bold mb-2">{{ $oferta->titulo }}</h2>
                            <p class="small mb-0">{{ \Illuminate\Support\Str::limit($oferta->descripcion, 110) }}</p>
                        </div>
                        <div class="ticket-corte"></div>
                        <div class="p-3 d-flex justify-content-between align-items-end">
                            <div>
                                <div class="text-decoration-line-through text-secondary small">${{ number_format($oferta->precio_regular, 2) }}</div>
                                <div class="precio">${{ number_format($oferta->precio_oferta, 2) }}</div>
                            </div>
                            <span class="badge text-bg-warning fs-6">-{{ $oferta->porcentajeDescuento() }}%</span>
                        </div>
                        <div class="px-3 pb-2 small text-secondary">Oferta válida hasta el {{ $oferta->fecha_fin->format('d/m/Y') }}</div>
                        <div class="px-3 pb-3 mt-auto">
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-cupon w-100">Inicia sesión para comprar</a>
                            @else
                                @if(auth()->user()->rol === 'cliente')
                                    <button type="button" class="btn btn-primary w-100" disabled>Comprar (disponible en la fase final)</button>
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
