@extends('layouts.app')

@section('titulo', 'Registro de cliente')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="panel p-4">
            <h1 class="h3 fw-bold mb-1">Crea tu cuenta de cliente</h1>
            <p class="text-secondary">Con tu cuenta podrás comprar cupones y consultar tus facturas.</p>

            @if($errors->any())
                <div class="alert alert-danger" role="alert">Revisa los campos marcados en rojo.</div>
            @endif

            <form method="POST" action="{{ route('cliente.registro') }}" novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-6"><x-campo name="nombre" label="Nombre completo" autocomplete="given-name" /></div>
                    <div class="col-md-6"><x-campo name="apellidos" label="Apellidos" autocomplete="family-name" /></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><x-campo name="dui" label="DUI" placeholder="00000000-0" maxlength="10" ayuda="Formato: 8 dígitos, guion y dígito verificador." /></div>
                    <div class="col-md-6"><x-campo name="fecha_nacimiento" label="Fecha de nacimiento" type="date" :max="now()->subYears(18)->toDateString()" ayuda="Debes ser mayor de 18 años." /></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><x-campo name="usuario" label="Usuario" autocomplete="username" ayuda="Entre 4 y 30 caracteres, sin espacios." /></div>
                    <div class="col-md-6"><x-campo name="email" label="Correo electrónico" type="email" autocomplete="email" /></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><x-campo name="password" label="Contraseña" type="password" autocomplete="new-password" ayuda="Mínimo 8 caracteres, con letras y números." /></div>
                    <div class="col-md-6"><x-campo name="password_confirmation" label="Confirmar contraseña" type="password" autocomplete="new-password" /></div>
                </div>

                <button type="submit" class="btn btn-primary">Crear cuenta</button>
                <a href="{{ route('login') }}" class="btn btn-link">Ya tengo cuenta</a>
            </form>
        </div>
    </div>
</div>
@endsection
