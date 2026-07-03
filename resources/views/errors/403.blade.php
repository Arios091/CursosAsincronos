@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-shield-alt fa-4x" style="color: #dc3545;"></i>
                    </div>
                    <h4 class="font-weight-bold" style="color: #dc3545;">Acceso Denegado</h4>
                    <p class="text-muted mt-3">No tienes permisos para acceder a esta pagina.</p>
                    <a href="{{ route('home') }}" class="btn text-white font-weight-bold px-4 mt-3" style="background: #0B5E2E;">
                        <i class="fas fa-home mr-1"></i> Ir al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
