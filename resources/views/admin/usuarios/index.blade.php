@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold" style="color: #0B5E2E;">Gestion de Usuarios</h4>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <div class="form-group mr-2">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o email..." value="{{ request('search') }}">
                </div>
                <div class="form-group mr-2">
                    <select name="role" class="form-control">
                        <option value="">Todos los roles</option>
                        <option value="admin_global" {{ request('role') === 'admin_global' ? 'selected' : '' }}>Admin Global</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="docente" {{ request('role') === 'docente' ? 'selected' : '' }}>Docente</option>
                        <option value="estudiante" {{ request('role') === 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                    </select>
                </div>
                <button type="submit" class="btn text-white mr-1" style="background: #0B5E2E;">Filtrar</button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Limpiar</a>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Cursos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                        <tr>
                            <td class="font-weight-bold">{{ $usuario->name }}</td>
                            <td><small>{{ $usuario->email }}</small></td>
                            <td>
                                <span class="badge badge-{{ $usuario->role === 'admin_global' ? 'danger' : ($usuario->role === 'admin' ? 'warning' : ($usuario->role === 'docente' ? 'info' : 'secondary')) }}">
                                    {{ $usuario->role }}
                                </span>
                            </td>
                            <td>
                                @php $countCursos = $usuario->progresos_count; @endphp
                                @if($countCursos > 0)
                                    <span class="badge badge-primary badge-pill">{{ $countCursos }}</span>
                                    @if($usuario->curso_en_progreso_id)
                                        <small class="text-success d-block">1 activo</small>
                                    @endif
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.usuarios.show', $usuario) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay usuarios registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $usuarios->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
