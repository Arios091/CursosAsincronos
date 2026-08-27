@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold" style="color: #0B5E2E;">
            @if(request()->has('gestion'))
            <i class="fas fa-edit mr-1"></i> Gestionar Cursos
            @else
            Cursos Disponibles
            @endif
        </h4>
        @if(auth()->user()->puedeGestionarCursos() && !request()->has('gestion'))
        <a href="{{ route('cursos.index', ['gestion' => 1]) }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-edit mr-1"></i> Gestionar
        </a>
        @endif
    </div>

    <div class="row">
        @forelse($cursos as $curso)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($curso->imagen)
                <img src="{{ storage_url($curso->imagen) }}" class="card-img-top" alt="{{ $curso->titulo }}" style="height: 180px; object-fit: cover;">
                @else
                <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 180px; background: linear-gradient(135deg, #0B5E2E, #0A4A24);">
                    <i class="fas fa-graduation-cap fa-4x text-white opacity-50"></i>
                </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title font-weight-bold">{{ $curso->titulo }}</h5>
                    <p class="card-text text-muted small">{{ Str::limit($curso->descripcion, 120) }}</p>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">Creado por: {{ $curso->user->name }}</small>
                            <span class="badge badge-{{ $curso->audiencia === 'docente' ? 'success' : 'info' }}">
                                {{ $curso->audiencia === 'docente' ? 'Docentes' : 'Estudiantes' }}
                            </span>
                        </div>
                        <a href="{{ route('cursos.show', $curso) }}" class="btn btn-block text-white" style="background: #0B5E2E;">
                            Ver Curso
                        </a>
                        @if(auth()->user()->puedeGestionarCursos() && request()->has('gestion'))
                        <div class="btn-group btn-block mt-2">
                            <a href="{{ route('cursos.editar', $curso) }}" class="btn btn-sm text-white font-weight-bold" style="background: #E67E22;">
                                <i class="fas fa-edit mr-1"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('cursos.destroy', $curso) }}" class="d-inline" id="delete-form-{{ $curso->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger font-weight-bold" onclick="showConfirm('¿Eliminar este curso? Se eliminaran todos los modulos, materiales y progresos asociados.', function() { document.getElementById('delete-form-{{ $curso->id }}').submit(); });">
                                    <i class="fas fa-trash mr-1"></i> Eliminar
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
            <p class="text-muted">No hay cursos disponibles en este momento.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
