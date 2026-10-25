@extends('layouts.app')

@php
    $titulos = [
        'cliente' => 'Ingreso de clientes',
        'empresa' => 'Ingreso de empresas',
        'admin' => 'Ingreso de administradores',
    ];
    $accion = $rol === 'cliente' ? route('login') : route($rol.'.login');
@endphp

@section('titulo', $titulos[$rol])

@section('contenido')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="panel p-4">
            <h1 class="h3 fw-bold mb-1">{{ $titulos[$rol] }}</h1>
            <p class="text-secondary">Escribe tu usuario o tu correo electrónico.</p>

            <form method="POST" action="{{ $accion }}" novalidate>
                @csrf
                <x-campo name="login" label="Usuario o correo electrónico" autocomplete="username" autofocus />
                <x-campo name="password" label="Contraseña" type="password" autocomplete="current-password" />

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="recordar" id="recordar" value="1">
                    <label class="form-check-label" for="recordar">Mantener la sesión iniciada</label>
                </div>

                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>

            <div class="mt-3 small d-flex flex-column gap-1">
                <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                @if($rol === 'cliente')
                    <span>¿Aún no tienes cuenta? <a href="{{ route('cliente.registro') }}">Regístrate como cliente</a></span>
                @elseif($rol === 'empresa')
                    <span>¿Quieres vender cupones? <a href="{{ route('empresa.registro') }}">Registra tu empresa</a></span>
                @endif
            </div>
        </div>

        <p class="small text-secondary mt-3 text-center">
            Otros accesos:
            @if($rol !== 'cliente')<a href="{{ route('login') }}">clientes</a>@endif
            @if($rol !== 'empresa')<a href="{{ route('empresa.login') }}">empresas</a>@endif
            @if($rol !== 'admin')<a href="{{ route('admin.login') }}">administradores</a>@endif
        </p>
    </div>
</div>
@endsection
