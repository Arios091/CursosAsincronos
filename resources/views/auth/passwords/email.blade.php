@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header text-white text-center font-weight-bold" style="background: linear-gradient(135deg, #0B5E2E, #0A4A24);">
                    Restablecer Contrasena
                </div>
                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success small" role="alert">{{ session('status') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger small" role="alert">{{ session('error') }}</div>
                    @endif

                    <p class="text-muted small mb-3">Ingresa tu correo institucional y te enviaremos un enlace para restablecer tu contrasena.</p>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">Correo Institucional (@unas.edu.pe)</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="ejemplo@unas.edu.pe">
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-block text-white font-weight-bold" style="background: #0B5E2E; border: none;">
                                Enviar Enlace
                            </button>
                        </div>

                        <hr>
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="small font-weight-bold" style="color: #0B5E2E;">
                                <i class="fas fa-arrow-left mr-1"></i> Volver a Iniciar Sesion
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
