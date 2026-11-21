@extends('layouts.app')

@section('titulo', 'Reportes')

@section('contenido')
    <h1 class="fw-bold mb-1">Reportes</h1>
    <p class="text-secondary">Ventas y ganancias de la plataforma. La ganancia es el total vendido × el porcentaje de comisión de cada empresa.</p>

    <form method="GET" class="panel p-3 mb-4 row g-2 align-items-end">
        <div class="col-sm-4 col-lg-3">
            <label for="desde" class="form-label small mb-1">Desde</label>
            <input type="date" id="desde" name="desde" value="{{ $desde }}" class="form-control @error('desde') is-invalid @enderror">
            @error('desde')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-sm-4 col-lg-3">
            <label for="hasta" class="form-label small mb-1">Hasta</label>
            <input type="date" id="hasta" name="hasta" value="{{ $hasta }}" class="form-control @error('hasta') is-invalid @enderror">
            @error('hasta')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-auto d-flex gap-2">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('admin.reportes') }}" class="btn btn-outline-secondary">Limpiar</a>
        </div>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="panel p-3 h-100">
                <div class="cifra">{{ number_format($totalCupones) }}</div>
                <div class="small text-secondary mt-1">Cupones vendidos</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel p-3 h-100">
                <div class="cifra">${{ number_format($totalVentas, 2) }}</div>
                <div class="small text-secondary mt-1">Total de ventas</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel p-3 h-100">
                <div class="cifra" style="color: var(--primario)">${{ number_format($totalGanancia, 2) }}</div>
                <div class="small text-secondary mt-1">Ganancias de La Cuponera (comisiones)</div>
            </div>
        </div>
    </div>

    <div class="panel mb-4">
        <h2 class="h5 fw-bold p-3 pb-0">Ventas y ganancias por empresa</h2>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th class="text-end">Cupones vendidos</th>
                        <th class="text-end">Ventas</th>
                        <th class="text-end">Comisión</th>
                        <th class="text-end">Ganancia La Cuponera</th>
                        <th class="text-end">Ganancia empresa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($porEmpresa as $fila)
                        <tr>
                            <td class="fw-semibold">{{ $fila->nombre }}</td>
                            <td class="text-end">{{ number_format($fila->cupones) }}</td>
                            <td class="text-end" style="min-width: 150px;">
                                ${{ number_format($fila->ventas, 2) }}
                                <div class="barra-datos mt-1" role="img" aria-label="{{ $totalVentas > 0 ? round($fila->ventas / $totalVentas * 100) : 0 }}% de las ventas">
                                    <span style="width: {{ $totalVentas > 0 ? round($fila->ventas / $totalVentas * 100) : 0 }}%"></span>
                                </div>
                            </td>
                            <td class="text-end">{{ number_format($fila->comision, 2) }}%</td>
                            <td class="text-end">${{ number_format($fila->ganancia_plataforma, 2) }}</td>
                            <td class="text-end">${{ number_format($fila->ganancia_empresa, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-4">No hay empresas aprobadas todavía.</td></tr>
                    @endforelse
                </tbody>
                @if($porEmpresa->isNotEmpty())
                    <tfoot>
                        <tr class="fw-bold">
                            <td>Total</td>
                            <td class="text-end">{{ number_format($totalCupones) }}</td>
                            <td class="text-end">${{ number_format($totalVentas, 2) }}</td>
                            <td></td>
                            <td class="text-end">${{ number_format($totalGanancia, 2) }}</td>
                            <td class="text-end">${{ number_format($totalVentas - $totalGanancia, 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <div class="panel">
        <h2 class="h5 fw-bold p-3 pb-0">Ofertas más vendidas</h2>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr><th>Oferta</th><th>Empresa</th><th class="text-end">Cupones</th><th class="text-end">Ventas</th></tr>
                </thead>
                <tbody>
                    @forelse($porOferta as $fila)
                        <tr>
                            <td class="fw-semibold">{{ $fila->titulo }}</td>
                            <td>{{ $fila->empresa }}</td>
                            <td class="text-end">{{ number_format($fila->cupones) }}</td>
                            <td class="text-end">${{ number_format($fila->ventas, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-4">No hay ventas en el período seleccionado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
