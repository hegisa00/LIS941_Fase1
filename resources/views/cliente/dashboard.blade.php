@extends('layouts.app')

@section('titulo', 'Mi cuenta')

@section('contenido')
    <h1 class="fw-bold mb-1">Hola, {{ $cliente->nombre }}</h1>
    <p class="text-secondary">Este es tu espacio como cliente.</p>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="panel p-4 h-100">
                <h2 class="h5 fw-bold">Mis datos</h2>
                <dl class="mb-0 small">
                    <dt>Nombre</dt><dd>{{ $cliente->nombreCompleto() }}</dd>
                    <dt>DUI</dt><dd>{{ $cliente->dui }}</dd>
                    <dt>Fecha de nacimiento</dt><dd>{{ $cliente->fecha_nacimiento->format('d/m/Y') }}</dd>
                    <dt>Usuario</dt><dd>{{ $cliente->user->usuario }}</dd>
                    <dt>Correo</dt><dd class="mb-0">{{ $cliente->user->email }}</dd>
                </dl>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="panel p-4 h-100">
                <h2 class="h5 fw-bold">Mis cupones y facturas</h2>
                <p class="text-secondary mb-3">Aún no has comprado cupones. Cuando lo hagas, aquí verás tu historial y tus facturas.</p>
                <a href="{{ route('home') }}" class="btn btn-cupon">Ver cupones disponibles</a>
            </div>
        </div>
    </div>
@endsection
