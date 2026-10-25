@extends('layouts.app')

@section('titulo', 'Administradores')

@section('contenido')
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
        <div>
            <h1 class="fw-bold mb-1">Administradores</h1>
            <p class="text-secondary mb-0">Personas con acceso al panel de administración.</p>
        </div>
        <a href="{{ route('admin.administradores.create') }}" class="btn btn-primary">Registrar administrador</a>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr><th>Nombre</th><th>Usuario</th><th>Correo</th><th>Registrado</th></tr>
                </thead>
                <tbody>
                    @foreach($administradores as $administrador)
                        <tr>
                            <td>{{ $administrador->nombre_completo }}</td>
                            <td>{{ $administrador->user->usuario }}</td>
                            <td>{{ $administrador->user->email }}</td>
                            <td>{{ $administrador->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
