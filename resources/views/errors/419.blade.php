@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-clock fa-4x" style="color: #C9A227;"></i>
                    </div>
                    <h4 class="font-weight-bold" style="color: #0B5E2E;">Sesion Expirada</h4>
                    <p class="text-muted mt-3">Tu sesion ha expirado por inactividad. Por favor, inicia sesion nuevamente.</p>
                    <a href="{{ route('login') }}" class="btn text-white font-weight-bold px-4 mt-3" style="background: #0B5E2E;">
                        <i class="fas fa-sign-in-alt mr-1"></i> Iniciar Sesion
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
