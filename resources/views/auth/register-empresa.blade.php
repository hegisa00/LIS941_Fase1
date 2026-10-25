@extends('layouts.app')

@section('titulo', 'Registro de empresa')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="panel p-4">
            <h1 class="h3 fw-bold mb-1">Registra tu empresa</h1>
            <p class="text-secondary">Un administrador revisará tu solicitud. Si la aprueba, podrás publicar ofertas.</p>

            @if($errors->any())
                <div class="alert alert-danger" role="alert">Revisa los campos marcados en rojo.</div>
            @endif

            <form method="POST" action="{{ route('empresa.registro') }}" novalidate>
                @csrf
                <x-campo name="nombre_empresa" label="Nombre de la empresa" autocomplete="organization" />
                <div class="row">
                    <div class="col-md-6"><x-campo name="nit" label="NIT" placeholder="0000-000000-000-0" maxlength="17" /></div>
                    <div class="col-md-6"><x-campo name="telefono" label="Teléfono" type="tel" placeholder="0000-0000" maxlength="9" autocomplete="tel" /></div>
                </div>
                <x-campo name="direccion" label="Dirección" autocomplete="street-address" />
                <x-campo name="email" label="Correo electrónico" type="email" autocomplete="email" />
                <div class="row">
                    <div class="col-md-4"><x-campo name="usuario" label="Usuario" autocomplete="username" ayuda="4 a 30 caracteres, sin espacios." /></div>
                    <div class="col-md-4"><x-campo name="password" label="Contraseña" type="password" autocomplete="new-password" ayuda="Mínimo 8, con letras y números." /></div>
                    <div class="col-md-4"><x-campo name="password_confirmation" label="Confirmar contraseña" type="password" autocomplete="new-password" /></div>
                </div>

                <button type="submit" class="btn btn-primary">Enviar solicitud</button>
                <a href="{{ route('empresa.login') }}" class="btn btn-link">Ya tengo cuenta</a>
            </form>
        </div>
    </div>
</div>
@endsection
