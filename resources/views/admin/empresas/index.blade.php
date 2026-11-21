@extends('layouts.app')

@section('titulo', 'Empresas')

@section('contenido')
    <h1 class="fw-bold mb-1">Empresas ofertantes</h1>
    <p class="text-secondary">Aprueba o rechaza las solicitudes de registro.</p>

    @php
        $pestanas = [
            'pendiente' => 'Pendientes',
            'aprobada' => 'Aprobadas',
            'rechazada' => 'Rechazadas',
            'todas' => 'Todas',
        ];
    @endphp

    <ul class="nav nav-pills mb-4 gap-1">
        @foreach($pestanas as $clave => $texto)
            <li class="nav-item">
                <a class="nav-link {{ $estado === $clave ? 'active pestana-activa' : 'text-body' }}"
                   href="{{ route('admin.empresas.index', ['estado' => $clave]) }}">
                    {{ $texto }}
                    <span class="badge text-bg-light">{{ $clave === 'todas' ? $conteos->sum() : ($conteos[$clave] ?? 0) }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    @forelse($empresas as $empresa)
        @php
            $bagAprobar = $errors->getBag('aprobar_'.$empresa->id);
            $bagRechazar = $errors->getBag('rechazar_'.$empresa->id);
        @endphp
        <div class="panel p-4 mb-3">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h2 class="h5 fw-bold mb-0">{{ $empresa->nombre }}</h2>
                    <div class="small text-secondary">Solicitud del {{ $empresa->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <span class="badge {{ $empresa->claseEstado() }}">{{ ucfirst($empresa->estado) }}</span>
            </div>

            <dl class="row small mt-3 mb-0">
                <dt class="col-sm-3">NIT</dt><dd class="col-sm-9">{{ $empresa->nit }}</dd>
                <dt class="col-sm-3">Dirección</dt><dd class="col-sm-9">{{ $empresa->direccion }}</dd>
                <dt class="col-sm-3">Teléfono</dt><dd class="col-sm-9">{{ $empresa->telefono }}</dd>
                <dt class="col-sm-3">Correo</dt><dd class="col-sm-9">{{ $empresa->user->email }}</dd>
                <dt class="col-sm-3">Usuario</dt><dd class="col-sm-9">{{ $empresa->user->usuario }}</dd>
                @if($empresa->estado === 'aprobada')
                    <dt class="col-sm-3">Comisión</dt><dd class="col-sm-9">{{ number_format($empresa->comision, 2) }}%</dd>
                @endif
                @if($empresa->estado === 'rechazada')
                    <dt class="col-sm-3">Motivo del rechazo</dt><dd class="col-sm-9">{{ $empresa->motivo_rechazo }}</dd>
                @endif
            </dl>

            @if($empresa->estado !== 'aprobada')
                <hr>
                <div class="row g-3">
                    <div class="col-lg-6">
                        <form method="POST" action="{{ route('admin.empresas.aprobar', $empresa) }}" novalidate>
                            @csrf
                            <label class="form-label small fw-semibold" for="comision-{{ $empresa->id }}">Aprobar con comisión (%)</label>
                            <div class="input-group">
                                <input type="text" inputmode="decimal" id="comision-{{ $empresa->id }}" name="comision"
                                       value="{{ $bagAprobar->any() ? old('comision') : '' }}" placeholder="Ej. 10"
                                       class="form-control {{ $bagAprobar->has('comision') ? 'is-invalid' : '' }}">
                                <button type="submit" class="btn btn-primary">Aprobar</button>
                                @if($bagAprobar->has('comision'))
                                    <div class="invalid-feedback">{{ $bagAprobar->first('comision') }}</div>
                                @endif
                            </div>
                        </form>
                    </div>

                    @if($empresa->estado === 'pendiente')
                        <div class="col-lg-6">
                            <form method="POST" action="{{ route('admin.empresas.rechazar', $empresa) }}" novalidate>
                                @csrf
                                <label class="form-label small fw-semibold" for="motivo-{{ $empresa->id }}">Rechazar indicando el motivo</label>
                                <div class="input-group">
                                    <input type="text" id="motivo-{{ $empresa->id }}" name="motivo_rechazo"
                                           value="{{ $bagRechazar->any() ? old('motivo_rechazo') : '' }}" placeholder="Ej. NIT no verificable"
                                           class="form-control {{ $bagRechazar->has('motivo_rechazo') ? 'is-invalid' : '' }}">
                                    <button type="submit" class="btn btn-outline-danger">Rechazar</button>
                                    @if($bagRechazar->has('motivo_rechazo'))
                                        <div class="invalid-feedback">{{ $bagRechazar->first('motivo_rechazo') }}</div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @empty
        <div class="panel p-4 text-center">
            <p class="mb-0 text-secondary">No hay empresas en esta categoría.</p>
        </div>
    @endforelse

    <div class="mt-3">{{ $empresas->links('pagination::bootstrap-5') }}</div>
@endsection
