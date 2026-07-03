@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if($progreso && $progreso->completado)
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    <h3 class="font-weight-bold" style="color: #0B5E2E;">Certificado Valido</h3>
                    <p class="text-muted">El siguiente certificado ha sido verificado exitosamente:</p>

                    <div class="border rounded p-4 mt-4 text-left" style="background: #f9fafb;">
                        <p><strong>Codigo:</strong> {{ $codigo }}</p>
                        <p><strong>Estudiante:</strong> {{ $progreso->user->name }}</p>
                        <p><strong>Curso:</strong> {{ $progreso->curso->titulo }}</p>
                        <p><strong>Fecha de finalizacion:</strong> {{ $progreso->updated_at->format('d/m/Y') }}</p>
                    </div>

                    <div class="mt-3">
                        <div style="display: inline-block; padding: 10px; background: #fff; border: 1px solid #ddd;">
                            {!! QrCode::size(150)->generate(url('/verificar/' . $codigo)) !!}
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-times-circle fa-5x text-danger"></i>
                    </div>
                    <h3 class="font-weight-bold text-danger">Certificado No Encontrado</h3>
                    <p class="text-muted">El codigo ingresado no corresponde a un certificado valido.</p>
                    <p class="text-muted small">Verifica que el codigo sea correcto e intenta nuevamente.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
