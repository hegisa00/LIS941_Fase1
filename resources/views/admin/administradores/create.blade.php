@extends('layouts.app')

@section('titulo', 'Registrar administrador')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="panel p-4">
            <h1 class="h3 fw-bold mb-1">Registrar administrador</h1>
            <p class="text-secondary">La persona podrá ingresar desde la pantalla de acceso de administradores.</p>

            @if($errors->any())
                <div class="alert alert-danger" role="alert">Revisa los campos marcados en rojo.</div>
            @endif

            <form method="POST" action="{{ route('admin.administradores.store') }}" novalidate>
                @csrf
                <x-campo name="nombre_completo" label="Nombre completo" />
                <div class="row">
                    <div class="col-md-6"><x-campo name="usuario" label="Usuario" ayuda="4 a 30 caracteres, sin espacios." /></div>
                    <div class="col-md-6"><x-campo name="email" label="Correo electrónico" type="email" /></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><x-campo name="password" label="Contraseña" type="password" autocomplete="new-password" ayuda="Mínimo 8, con letras y números." /></div>
                    <div class="col-md-6"><x-campo name="password_confirmation" label="Confirmar contraseña" type="password" autocomplete="new-password" /></div>
                </div>

                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="{{ route('admin.administradores.index') }}" class="btn btn-link">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
