@extends('layouts.app')

@section('titulo', 'Mis cupones')

@section('contenido')
    <h1 class="fw-bold mb-1">Mis cupones</h1>
    <p class="text-secondary">Historial de tus compras, códigos de cupón y facturas.</p>

    @if($compras->isEmpty())
        <div class="panel p-4 text-center">
            <p class="mb-1 fw-semibold">Aún no has comprado cupones.</p>
            <a href="{{ route('home') }}" class="btn btn-cupon mt-2">Ver cupones disponibles</a>
        </div>
    @else
        @foreach($compras as $compra)
            <div class="panel p-4 mb-3">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <div class="small text-secondary">{{ $compra->oferta->empresa->nombre }}</div>
                        <h2 class="h5 fw-bold mb-0">{{ $compra->oferta->titulo }}</h2>
                        <div class="small text-secondary">
                            {{ $compra->created_at->format('d/m/Y H:i') }} · Factura {{ $compra->numero_factura }}
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold fs-5">${{ number_format($compra->total, 2) }}</div>
                        <div class="small text-secondary">{{ $compra->cantidad }} × ${{ number_format($compra->precio_unitario, 2) }}</div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    @foreach($compra->cupones as $cupon)
                        <span class="border rounded px-2 py-1 small">
                            <span class="font-monospace fw-semibold">{{ $cupon->codigo }}</span>
                            <span class="badge {{ $cupon->claseEstado() }} ms-1">{{ ucfirst($cupon->estadoActual()) }}</span>
                            <button type="button" class="btn btn-link btn-sm p-0 ms-1" data-copiar="{{ $cupon->codigo }}">Copiar</button>
                        </span>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="small text-secondary">Canjear antes del {{ $compra->oferta->fecha_limite_canje->format('d/m/Y') }}</span>
                    <a href="{{ route('compras.factura', $compra->id) }}" class="btn btn-sm btn-outline-secondary">Ver factura</a>
                </div>
            </div>
        @endforeach

        <div class="mt-4">{{ $compras->links('pagination::bootstrap-5') }}</div>
    @endif
@endsection
