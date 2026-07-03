@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-envelope-open-text fa-4x" style="color: #0B5E2E;"></i>
                    </div>
                    <h4 class="font-weight-bold" style="color: #0B5E2E;">Revisa tu Correo</h4>
                    <p class="text-muted mt-3">
                        Hemos enviado un enlace de verificacion a tu correo electronico.
                        Por favor, revisa tu bandeja de entrada y haz clic en el enlace para activar tu cuenta.
                    </p>
                    <div class="alert alert-info mt-4">
                        <small>
                            <i class="fas fa-info-circle mr-1"></i>
                            El enlace expirara en 24 horas. Si no encuentras el correo, revisa la bandeja de spam.
                        </small>
                    </div>
                    <a href="{{ route('login') }}" class="btn text-white font-weight-bold mt-3" style="background: #0B5E2E;">
                        Ir a Iniciar Sesion
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
