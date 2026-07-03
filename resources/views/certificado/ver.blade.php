@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-certificate fa-5x" style="color: #C9A227;"></i>
                    </div>
                    <h3 class="font-weight-bold" style="color: #0B5E2E;">Certificado de Finalizacion</h3>
                    <p class="text-muted">Curso: <strong>{{ $curso->titulo }}</strong></p>
                    <p>Codigo de verificacion: <strong>{{ $codigo }}</strong></p>

                    <div class="border p-4 mt-4 text-left" style="background: #f9fafb;">
                        <p><strong>Estudiante:</strong> {{ $user->name }}</p>
                        <p><strong>Curso:</strong> {{ $curso->titulo }}</p>
                        <p><strong>Fecha de finalizacion:</strong> {{ $progresoCurso->updated_at->format('d/m/Y') }}</p>
                        <p><strong>Codigo:</strong> {{ $codigo }}</p>
                        <p class="mt-3 small text-muted">
                            Este certificado puede ser verificado en:
                            <a href="{{ url('/verificar/' . $codigo) }}" target="_blank">{{ url('/verificar/' . $codigo) }}</a>
                        </p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('certificado.descargar', $curso) }}" class="btn text-white font-weight-bold px-4 mr-2" style="background: #0B5E2E;">
                            <i class="fas fa-download mr-1"></i> Descargar PDF
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary font-weight-bold px-4">
                            Ir al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
