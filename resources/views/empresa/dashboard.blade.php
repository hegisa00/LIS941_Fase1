@extends('layouts.app')

@section('titulo', 'Panel de empresa')

@section('contenido')
    <h1 class="fw-bold mb-1">{{ $empresa->nombre }}</h1>
    <p class="text-secondary">Panel de empresa ofertante.</p>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="panel p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h2 class="h5 fw-bold mb-0">Estado de tu solicitud</h2>
                    <span class="badge {{ $empresa->claseEstado() }}">{{ ucfirst($empresa->estado) }}</span>
                </div>

                @if($empresa->estado === 'pendiente')
                    <p class="mb-0">Tu solicitud está en revisión. Cuando un administrador la apruebe, podrás publicar ofertas.</p>
                @elseif($empresa->estado === 'aprobada')
                    <p>Tu empresa fue aprobada el {{ $empresa->fecha_resolucion?->format('d/m/Y') }}.
                       La comisión por cada cupón vendido es de <strong>{{ number_format($empresa->comision, 2) }}%</strong>.</p>
                    <p class="text-secondary mb-0">La publicación de ofertas se habilitará en la fase final del proyecto.</p>
                @else
                    <p class="mb-1">Tu solicitud fue rechazada.</p>
                    <p class="mb-0"><strong>Motivo:</strong> {{ $empresa->motivo_rechazo }}</p>
                @endif
            </div>
        </div>
        <div class="col-lg-5">
            <div class="panel p-4 h-100">
                <h2 class="h5 fw-bold">Datos de la empresa</h2>
                <dl class="mb-0 small">
                    <dt>NIT</dt><dd>{{ $empresa->nit }}</dd>
                    <dt>Dirección</dt><dd>{{ $empresa->direccion }}</dd>
                    <dt>Teléfono</dt><dd>{{ $empresa->telefono }}</dd>
                    <dt>Correo</dt><dd class="mb-0">{{ $empresa->user->email }}</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
