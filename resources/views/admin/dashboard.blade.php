@extends('layouts.app')

@section('titulo', 'Panel de administración')

@section('contenido')
    <h1 class="fw-bold mb-1">Panel de administración</h1>
    <p class="text-secondary">Resumen general de La Cuponera SV.</p>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <a href="{{ route('admin.empresas.index', ['estado' => 'pendiente']) }}" class="panel p-3 d-block text-decoration-none h-100">
                <div class="cifra">{{ $pendientes }}</div>
                <div class="small text-secondary mt-1">Solicitudes pendientes</div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('admin.empresas.index', ['estado' => 'aprobada']) }}" class="panel p-3 d-block text-decoration-none h-100">
                <div class="cifra">{{ $aprobadas }}</div>
                <div class="small text-secondary mt-1">Empresas aprobadas</div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <div class="panel p-3 h-100">
                <div class="cifra">{{ $clientes }}</div>
                <div class="small text-secondary mt-1">Clientes registrados</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('admin.administradores.index') }}" class="panel p-3 d-block text-decoration-none h-100">
                <div class="cifra">{{ $administradores }}</div>
                <div class="small text-secondary mt-1">Administradores</div>
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <a href="{{ route('admin.reportes') }}" class="panel p-3 d-block text-decoration-none h-100">
                <div class="cifra">{{ number_format($cuponesVendidos) }}</div>
                <div class="small text-secondary mt-1">Cupones vendidos</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.reportes') }}" class="panel p-3 d-block text-decoration-none h-100">
                <div class="cifra">${{ number_format($totalVentas, 2) }}</div>
                <div class="small text-secondary mt-1">Ventas totales</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.reportes') }}" class="panel p-3 d-block text-decoration-none h-100">
                <div class="cifra" style="color: var(--primario)">${{ number_format($gananciaTotal, 2) }}</div>
                <div class="small text-secondary mt-1">Ganancia por comisiones</div>
            </a>
        </div>
    </div>

    @if($pendientes > 0)
        <div class="alert alert-warning d-flex justify-content-between align-items-center" role="alert">
            <span>Tienes <strong>{{ $pendientes }}</strong> {{ $pendientes === 1 ? 'solicitud de empresa pendiente' : 'solicitudes de empresas pendientes' }} de revisar.</span>
            <a href="{{ route('admin.empresas.index', ['estado' => 'pendiente']) }}" class="btn btn-sm btn-primary">Revisar</a>
        </div>
    @endif

    <div class="panel p-4">
        <h2 class="h5 fw-bold">Accesos rápidos</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.empresas.index') }}" class="btn btn-primary">Revisar solicitudes de empresas</a>
            <a href="{{ route('admin.reportes') }}" class="btn btn-cupon">Ver reportes de ventas y ganancias</a>
            <a href="{{ route('admin.administradores.create') }}" class="btn btn-outline-secondary">Registrar administrador</a>
        </div>
    </div>
@endsection
