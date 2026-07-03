@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-trophy fa-5x" style="color: #C9A227;"></i>
                    </div>
                    <h2 class="font-weight-bold" style="color: #0B5E2E;">Felicidades!</h2>
                    <p class="lead">Has completado exitosamente el curso:</p>
                    <h4 class="font-weight-bold mb-4">{{ $curso->titulo }}</h4>

                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="{{ route('certificado.ver', $curso) }}" class="btn text-white font-weight-bold px-4 mr-2" style="background: #0B5E2E;">
                            <i class="fas fa-certificate mr-1"></i> Ver Certificado
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary font-weight-bold px-4">
                            <i class="fas fa-home mr-1"></i> Ir al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
