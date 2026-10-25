@extends('layouts.app')

@section('titulo', 'Recuperar contraseña')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="panel p-4">
            <h1 class="h3 fw-bold mb-1">Recuperar contraseña</h1>
            <p class="text-secondary">Sirve para clientes, empresas y administradores. Escribe el correo de tu cuenta y te enviaremos un enlace.</p>

            @if(session('enlace_demo'))
                <div class="alert alert-info small" role="alert">
                    <strong>Modo desarrollo:</strong> el correo se guarda en <code>storage/logs/laravel.log</code>.
                    También puedes abrir el enlace directamente:
                    <a href="{{ session('enlace_demo') }}">restablecer contraseña</a>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" novalidate>
                @csrf
                <x-campo name="email" label="Correo electrónico" type="email" autocomplete="email" autofocus />
                <button type="submit" class="btn btn-primary w-100">Enviar enlace</button>
            </form>

            <p class="small mt-3 mb-0"><a href="{{ route('login') }}">Volver al inicio de sesión</a></p>
        </div>
    </div>
</div>
@endsection
