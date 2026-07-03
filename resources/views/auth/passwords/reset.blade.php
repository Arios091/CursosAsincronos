@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header text-white text-center font-weight-bold" style="background: linear-gradient(135deg, #0B5E2E, #0A4A24);">
                    Nueva Contrasena
                </div>
                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger small" role="alert">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="form-group">
                            <label for="password">Nueva Contrasena</label>
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
                                Restablecer Contrasena
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
