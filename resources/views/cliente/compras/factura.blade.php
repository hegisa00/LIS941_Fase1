@extends('layouts.app')

@section('titulo', 'Factura '.$compra->numero_factura)

@push('estilos')
<style>
    @media print {
        .barra, footer, .no-imprimir, .alert { display: none !important; }
        body { background: #fff; }
        .panel { border: 0; }
    }
</style>
@endpush

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-3 no-imprimir">
        <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Mis cupones</a>
        <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">Imprimir factura</button>
    </div>

    <div class="panel p-4 p-lg-5 mx-auto" style="max-width: 760px;">
        <div class="d-flex justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <div class="marca h3 mb-0" style="color: var(--primario)">La Cuponera <span>SV</span></div>
                <div class="small text-secondary">Factura electrónica</div>
            </div>
            <div class="text-lg-end">
                <div class="fw-bold">{{ $compra->numero_factura }}</div>
                <div class="small text-secondary">{{ $compra->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <div class="row g-3 mb-4 small">
            <div class="col-sm-6">
                <div class="text-secondary text-uppercase fw-semibold">Cliente</div>
                <div>{{ $compra->cliente->nombreCompleto() }}</div>
                <div>DUI {{ $compra->cliente->dui }}</div>
                <div>{{ $compra->cliente->user->email }}</div>
            </div>
            <div class="col-sm-6">
                <div class="text-secondary text-uppercase fw-semibold">Empresa</div>
                <div>{{ $compra->oferta->empresa->nombre }}</div>
                <div>NIT {{ $compra->oferta->empresa->nit }}</div>
                <div>{{ $compra->oferta->empresa->direccion }}</div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr><th>Descripción</th><th class="text-center">Cant.</th><th class="text-end">Precio</th><th class="text-end">Subtotal</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $compra->oferta->titulo }}</td>
                        <td class="text-center">{{ $compra->cantidad }}</td>
                        <td class="text-end">${{ number_format($compra->precio_unitario, 2) }}</td>
                        <td class="text-end">${{ number_format($compra->total, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total pagado</th>
                        <th class="text-end fs-5">${{ number_format($compra->total, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <p class="small text-secondary">Pago con tarjeta terminada en {{ $compra->tarjeta_ultimos4 }}.</p>

        <h2 class="h6 fw-bold mt-4">Códigos de tus cupones</h2>
        <p class="small text-secondary">Presenta cada código en el establecimiento antes del
            <strong>{{ $compra->oferta->fecha_limite_canje->format('d/m/Y') }}</strong>.</p>
        <div class="row g-2">
            @foreach($compra->cupones as $cupon)
                <div class="col-sm-6">
                    <div class="ticket p-3 text-center">
                        <div class="font-monospace fw-bold fs-5">{{ $cupon->codigo }}</div>
                        <span class="badge {{ $cupon->claseEstado() }}">{{ ucfirst($cupon->estadoActual()) }}</span>
                        <div class="no-imprimir mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-copiar="{{ $cupon->codigo }}" data-sin-bloqueo>Copiar código</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
