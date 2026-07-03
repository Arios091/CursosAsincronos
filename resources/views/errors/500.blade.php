@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x" style="color: #dc3545;"></i>
                    </div>
                    <h4 class="font-weight-bold" style="color: #dc3545;">Error del Servidor</h4>
                    <p class="text-muted mt-3">Ocurrio un error inesperado. Intenta nuevamente mas tarde.</p>
                    <a href="{{ route('home') }}" class="btn text-white font-weight-bold px-4 mt-3" style="background: #0B5E2E;">
                        <i class="fas fa-home mr-1"></i> Ir al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
