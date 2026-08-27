@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        @if($curso->imagen)
        <img src="{{ storage_url($curso->imagen) }}" class="card-img-top" alt="{{ $curso->titulo }}" style="height: 250px; object-fit: cover;">
        @else
        <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 250px; background: linear-gradient(135deg, #0B5E2E, #0A4A24);">
            <i class="fas fa-graduation-cap fa-5x text-white"></i>
        </div>
        @endif
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3 class="font-weight-bold" style="color: #0B5E2E;">{{ $curso->titulo }}</h3>
                    <p class="text-muted">{{ $curso->descripcion }}</p>
                </div>
                <span class="badge badge-{{ $curso->audiencia === 'docente' ? 'success' : 'info' }} p-2">
                    {{ $curso->audiencia === 'docente' ? 'Docentes' : 'Estudiantes' }}
                </span>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">Creado por: {{ $curso->user->name }} | {{ $curso->modulos->count() }} modulo(s)</small>
                @php
                    $progresoCurso = \App\Models\ProgresoCurso::where('user_id', auth()->id())->where('curso_id', $curso->id)->first();
                @endphp
                @if(!$progresoCurso)
                <a href="#" class="btn text-white font-weight-bold px-4" style="background: #0B5E2E;"
                   onclick="event.preventDefault(); showConfirm('Deseas comenzar este curso?', function() { document.getElementById('start-form-{{ $curso->id }}').submit(); });">
                    Comenzar Curso
                </a>
                <form id="start-form-{{ $curso->id }}" action="{{ route('cursos.comenzar', $curso) }}" method="POST" class="d-none">
                    @csrf
                </form>
                @elseif(!$progresoCurso->completado)
                <a href="{{ route('mis-cursos', $curso) }}" class="btn text-white font-weight-bold px-4" style="background: #0B5E2E;">
                    Continuar Curso
                </a>
                @else
                <a href="{{ route('cursos.completado', $curso) }}" class="btn text-white font-weight-bold px-4" style="background: #0B5E2E;">
                    Ver Completado
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h5 class="font-weight-bold" style="color: #0B5E2E;">Modulos del Curso</h5>
        @foreach($curso->modulos as $modulo)
        <div class="card mt-3">
            <div class="card-header font-weight-bold">
                Modulo {{ $modulo->orden }}: {{ $modulo->titulo }}
            </div>
            <div class="card-body">
                @if($modulo->descripcion)
                <p class="text-muted">{{ $modulo->descripcion }}</p>
                @endif
                <div class="row">
                    @foreach($modulo->materiales as $material)
                    <div class="col-md-4 mb-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-{{ $material->tipo === 'video' ? 'video' : ($material->tipo === 'pdf' ? 'file-pdf' : 'question-circle') }} mr-2" style="color: #0B5E2E;"></i>
                            <span>{{ $material->titulo }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
