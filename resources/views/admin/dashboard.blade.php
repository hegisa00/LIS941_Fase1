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

    <div class="panel p-4">
        <h2 class="h5 fw-bold">Accesos rápidos</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.empresas.index') }}" class="btn btn-primary">Revisar solicitudes de empresas</a>
            <a href="{{ route('admin.administradores.create') }}" class="btn btn-outline-secondary">Registrar administrador</a>
        </div>
        <p class="small text-secondary mt-3 mb-0">Los reportes de ventas y ganancias se agregarán en la fase final.</p>
    </div>
@endsection
