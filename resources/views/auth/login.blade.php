@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header text-white text-center font-weight-bold" style="background: linear-gradient(135deg, #0B5E2E, #0A4A24);">
                    <img src="{{ asset('images/unas-logo.png') }}" alt="UNAS" style="max-width:36px;display:block;margin:0 auto 6px auto;">
                    Iniciar Sesion - SGD
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">Correo Electronico</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Contrasena</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Recordarme</label>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-block text-white font-weight-bold" style="background: #0B5E2E; border: none;">
                                Ingresar
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            @if (Route::has('password.request'))
                                <a class="text-muted small" href="{{ route('password.request') }}">Olvidaste tu contrasena?</a>
                            @endif
                        </div>

                        <hr>
                        <div class="text-center">
                            <span class="text-muted small">No tienes cuenta?</span>
                            <a href="{{ route('register') }}" class="small font-weight-bold" style="color: #0B5E2E;">Crear Cuenta</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
