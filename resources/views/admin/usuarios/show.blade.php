@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.usuarios.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item active">{{ $user->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x" style="color: #0B5E2E;"></i>
                    </div>
                    <h5 class="font-weight-bold">{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <span class="badge badge-{{ $user->role === 'admin_global' ? 'danger' : ($user->role === 'admin' ? 'warning' : ($user->role === 'docente' ? 'info' : 'secondary')) }} p-2">
                        {{ $user->role }}
                    </span>
                    <p class="mt-2 small text-muted">Registrado: {{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            @if($user->curso_en_progreso_id)
            <div class="card shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold">Curso Activo</h6>
                    <p>{{ $user->cursoEnProgreso->titulo ?? 'N/A' }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header font-weight-bold">Editar Usuario</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.usuarios.update', $user) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Rol</label>
                            <select name="role" class="form-control">
                                <option value="docente" {{ $user->role === 'docente' ? 'selected' : '' }}>Docente</option>
                                <option value="estudiante" {{ $user->role === 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="admin_global" {{ $user->role === 'admin_global' ? 'selected' : '' }}>Admin Global</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nueva Contrasena (dejar vacio para mantener la actual)</label>
                            <input type="password" name="password" class="form-control" minlength="8" placeholder="Minimo 8 caracteres">
                        </div>
                        <div class="form-group">
                            <label>Confirmar Contrasena</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la nueva contrasena">
                        </div>
                        <button type="submit" class="btn text-white" style="background: #0B5E2E;">Guardar Cambios</button>
                    </form>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header font-weight-bold">Eliminar Usuario</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.usuarios.destroy', $user) }}" id="delete-user-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger" onclick="showConfirm('Estas seguro de eliminar este usuario? Esta accion no se puede deshacer.', function() { document.getElementById('delete-user-form').submit(); });">Eliminar Usuario</button>
                    </form>
                </div>
            </div>

            @if($progresos->count() > 0)
            <div class="card shadow">
                <div class="card-header font-weight-bold">
                    <i class="fas fa-book-open mr-1"></i> Cursos Inscritos ({{ $progresos->count() }})
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Curso</th>
                                    <th>Progreso</th>
                                    <th>Inscripcion</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($progresos as $progreso)
                                <tr>
                                    <td>
                                        <a href="{{ route('cursos.show', $progreso->curso) }}" class="font-weight-bold" style="color: #0B5E2E;">
                                            {{ $progreso->curso->titulo }}
                                        </a>
                                    </td>
                                    <td style="min-width: 150px;">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $progreso->progreso }}%; background: {{ $progreso->completado ? '#28a745' : '#C9A227' }};"></div>
                                            </div>
                                            <small class="ml-2 font-weight-bold">{{ $progreso->progreso }}%</small>
                                        </div>
                                    </td>
                                    <td><small class="text-muted">{{ $progreso->created_at->format('d/m/Y') }}</small></td>
                                    <td>
                                        @if($progreso->completado)
                                            <span class="badge badge-success">Completado</span>
                                            <small class="text-muted d-block">{{ $progreso->updated_at->format('d/m/Y') }}</small>
                                        @else
                                            <span class="badge badge-warning">En curso</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="card shadow">
                <div class="card-body text-center py-4">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Este usuario no esta inscrito en ningun curso.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
