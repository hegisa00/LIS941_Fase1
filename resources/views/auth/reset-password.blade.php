@extends('layouts.app')

@section('titulo', 'Nueva contraseña')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="panel p-4">
            <h1 class="h3 fw-bold mb-1">Elige una nueva contraseña</h1>
            <p class="text-secondary">Cuenta: <strong>{{ $email }}</strong></p>

            <form method="POST" action="{{ route('password.update') }}" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                @error('email')
                    <div class="alert alert-danger" role="alert">{{ $message }}</div>
                @enderror

                <x-campo name="password" label="Nueva contraseña" type="password" autocomplete="new-password" ayuda="Mínimo 8 caracteres, con letras y números." autofocus />
                <x-campo name="password_confirmation" label="Confirmar contraseña" type="password" autocomplete="new-password" />

                <button type="submit" class="btn btn-primary w-100">Guardar contraseña</button>
            </form>
        </div>
    </div>
</div>
@endsection
