@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header text-white font-weight-bold" style="background: linear-gradient(135deg, #0B5E2E, #0A4A24);">
                    Dashboard
                </div>
                <div class="card-body">
                    <h4>Bienvenido, {{ auth()->user()->name }}!</h4>
                    <p class="text-muted">Rol: <strong>{{ ucfirst(auth()->user()->role) }}</strong></p>

                    @if($cursoEnProgreso)
                    <div class="alert alert-info">
                        <strong>Curso en progreso:</strong>
                        <a href="{{ route('mis-cursos', $cursoEnProgreso) }}">{{ $cursoEnProgreso->titulo }}</a>
                    </div>
                    @else
                    <div class="alert alert-secondary">
                        No tienes ningun curso en progreso.
                        <a href="{{ route('cursos.index') }}" class="font-weight-bold">Ver cursos disponibles</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($progresos->count() > 0)
        <div class="col-12">
            <div class="card">
                <div class="card-header font-weight-bold">Historial de Cursos</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Curso</th>
                                    <th>Progreso</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($progresos as $progreso)
                                <tr>
                                    <td>{{ $progreso->curso->titulo }}</td>
                                    <td>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar" style="width: {{ $progreso->progreso }}%; background: #C9A227;"></div>
                                        </div>
                                        <small>{{ $progreso->progreso }}%</small>
                                    </td>
                                    <td>
                                        @if($progreso->completado)
                                            <span class="badge badge-success">Completado</span>
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
        </div>
        @endif
    </div>
</div>
@endsection
