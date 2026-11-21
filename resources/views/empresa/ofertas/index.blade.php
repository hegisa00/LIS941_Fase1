@extends('layouts.app')

@section('titulo', 'Mis ofertas')

@section('contenido')
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
        <div>
            <h1 class="fw-bold mb-1">Mis ofertas</h1>
            <p class="text-secondary mb-0">Ofertas publicadas por {{ auth()->user()->empresa->nombre }}.</p>
        </div>
        <a href="{{ route('empresa.ofertas.create') }}" class="btn btn-cupon">+ Nueva oferta</a>
    </div>

    @if($ofertas->isEmpty())
        <div class="panel p-4 text-center">
            <p class="mb-1 fw-semibold">Aún no has publicado ofertas.</p>
            <p class="text-secondary mb-0">Crea la primera para que los clientes puedan comprar tus cupones.</p>
        </div>
    @else
        <div class="panel">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Oferta</th>
                            <th class="text-end">Precio</th>
                            <th>Vigencia</th>
                            <th class="text-center">Vendidos</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ofertas as $oferta)
                            <tr>
                                <td class="fw-semibold">{{ $oferta->titulo }}</td>
                                <td class="text-end">
                                    <span class="text-decoration-line-through text-secondary small">${{ number_format($oferta->precio_regular, 2) }}</span><br>
                                    <strong>${{ number_format($oferta->precio_oferta, 2) }}</strong>
                                </td>
                                <td class="small">
                                    {{ $oferta->fecha_inicio->format('d/m/Y') }} – {{ $oferta->fecha_fin->format('d/m/Y') }}<br>
                                    <span class="text-secondary">Canje hasta {{ $oferta->fecha_limite_canje->format('d/m/Y') }}</span>
                                </td>
                                <td class="text-center">
                                    {{ $oferta->cuponesVendidos() }}{{ $oferta->cantidad_cupones !== null ? ' / '.$oferta->cantidad_cupones : '' }}
                                </td>
                                <td>
                                    <span class="badge {{ $oferta->estado === 'disponible' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ $oferta->estado === 'disponible' ? 'Disponible' : 'No disponible' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('empresa.ofertas.edit', $oferta->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $ofertas->links('pagination::bootstrap-5') }}</div>
    @endif
@endsection
