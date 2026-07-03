@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header text-white text-center font-weight-bold" style="background: linear-gradient(135deg, #0B5E2E, #0A4A24);">
                    Completa tu Perfil
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small text-center mb-3">
                        Solo un paso mas. Ingresa tus nombres y apellidos completos para finalizar el registro.
                    </p>

                    <form method="POST" action="{{ route('completar.perfil.guardar') }}">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="primer_nombre">Primer Nombre <span class="text-danger">*</span></label>
                                <input id="primer_nombre" type="text"
                                    class="form-control @error('primer_nombre') is-invalid @enderror"
                                    name="primer_nombre" value="{{ old('primer_nombre', $suggestedFirstName) }}" required>
                                @error('primer_nombre')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="segundo_nombre">Segundo Nombre</label>
                                <input id="segundo_nombre" type="text"
                                    class="form-control @error('segundo_nombre') is-invalid @enderror"
                                    name="segundo_nombre" value="{{ old('segundo_nombre') }}">
                                @error('segundo_nombre')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="primer_apellido">Apellido Paterno <span class="text-danger">*</span></label>
                                <input id="primer_apellido" type="text"
                                    class="form-control @error('primer_apellido') is-invalid @enderror"
                                    name="primer_apellido" value="{{ old('primer_apellido', $suggestedLastName) }}" required>
                                @error('primer_apellido')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="segundo_apellido">Apellido Materno</label>
                                <input id="segundo_apellido" type="text"
                                    class="form-control @error('segundo_apellido') is-invalid @enderror"
                                    name="segundo_apellido" value="{{ old('segundo_apellido') }}">
                                @error('segundo_apellido')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-0 mt-3">
                            <button type="submit" class="btn btn-block text-white font-weight-bold" style="background: #0B5E2E; border: none;">
                                <i class="fas fa-check mr-1"></i> Finalizar Registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
