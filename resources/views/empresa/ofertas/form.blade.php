@extends('layouts.app')

@php $editando = $oferta->exists; @endphp

@section('titulo', $editando ? 'Editar oferta' : 'Nueva oferta')

@section('contenido')
    <h1 class="fw-bold mb-1">{{ $editando ? 'Editar oferta' : 'Publicar una oferta' }}</h1>
    <p class="text-secondary">Los clientes verán la oferta mientras esté disponible y dentro de sus fechas.</p>

    <form method="POST" novalidate
          action="{{ $editando ? route('empresa.ofertas.update', $oferta->id) : route('empresa.ofertas.store') }}"
          class="panel p-4">
        @csrf
        @if($editando) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-12">
                <label for="titulo" class="form-label">Título de la oferta</label>
                <input type="text" id="titulo" name="titulo" maxlength="150" required
                       value="{{ old('titulo', $oferta->titulo) }}"
                       class="form-control @error('titulo') is-invalid @enderror">
                @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="precio_regular" class="form-label">Precio regular (US$)</label>
                <input type="number" step="0.01" min="0.01" id="precio_regular" name="precio_regular" required
                       value="{{ old('precio_regular', $oferta->precio_regular) }}"
                       class="form-control @error('precio_regular') is-invalid @enderror">
                @error('precio_regular')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="precio_oferta" class="form-label">Precio de oferta (US$)</label>
                <input type="number" step="0.01" min="0.01" id="precio_oferta" name="precio_oferta" required
                       value="{{ old('precio_oferta', $oferta->precio_oferta) }}"
                       class="form-control @error('precio_oferta') is-invalid @enderror">
                @error('precio_oferta')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="fecha_inicio" class="form-label">Inicio de la oferta</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" required
                       value="{{ old('fecha_inicio', $oferta->fecha_inicio?->format('Y-m-d')) }}"
                       class="form-control @error('fecha_inicio') is-invalid @enderror">
                @error('fecha_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="fecha_fin" class="form-label">Fin de la oferta</label>
                <input type="date" id="fecha_fin" name="fecha_fin" required
                       value="{{ old('fecha_fin', $oferta->fecha_fin?->format('Y-m-d')) }}"
                       class="form-control @error('fecha_fin') is-invalid @enderror">
                @error('fecha_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="fecha_limite_canje" class="form-label">Fecha límite para canjear</label>
                <input type="date" id="fecha_limite_canje" name="fecha_limite_canje" required
                       value="{{ old('fecha_limite_canje', $oferta->fecha_limite_canje?->format('Y-m-d')) }}"
                       class="form-control @error('fecha_limite_canje') is-invalid @enderror">
                @error('fecha_limite_canje')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="cantidad_cupones" class="form-label">Cantidad de cupones <span class="text-secondary">(opcional)</span></label>
                <input type="number" min="1" id="cantidad_cupones" name="cantidad_cupones"
                       value="{{ old('cantidad_cupones', $oferta->cantidad_cupones) }}"
                       class="form-control @error('cantidad_cupones') is-invalid @enderror">
                <div class="form-text">Déjalo vacío si no quieres limitar la cantidad de cupones.</div>
                @error('cantidad_cupones')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select @error('estado') is-invalid @enderror">
                    <option value="disponible" @selected(old('estado', $oferta->estado) === 'disponible')>Disponible</option>
                    <option value="no_disponible" @selected(old('estado', $oferta->estado) === 'no_disponible')>No disponible</option>
                </select>
                @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4" maxlength="2000" required
                          class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $oferta->descripcion) }}</textarea>
                @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">{{ $editando ? 'Guardar cambios' : 'Publicar oferta' }}</button>
            <a href="{{ route('empresa.ofertas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
@endsection
