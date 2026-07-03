@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header text-white text-center font-weight-bold" style="background: linear-gradient(135deg, #0B5E2E, #0A4A24);">
                    Crear Cuenta
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small text-center mb-3">Ingresa tu correo institucional de la UNAS para crear tu cuenta.</p>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">Correo Institucional (@unas.edu.pe)</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="nombre.apellido@unas.edu.pe">
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Contrasena</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" required autocomplete="new-password" placeholder="Minimo 8 caracteres">
                            <small class="form-text text-muted">Debe tener al menos 8 caracteres, una mayuscula y un numero.</small>
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">Confirmar Contrasena</label>
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-block text-white font-weight-bold" style="background: #0B5E2E; border: none;">
                                Registrarse
                            </button>
                        </div>

                        <hr>
                        <div class="text-center">
                            <span class="text-muted small">Ya tienes cuenta?</span>
                            <a href="{{ route('login') }}" class="small font-weight-bold" style="color: #0B5E2E;">Iniciar Sesion</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
