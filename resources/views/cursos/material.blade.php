@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent pl-0">
                    <li class="breadcrumb-item"><a href="{{ route('cursos.show', $material->modulo->curso) }}">{{ $material->modulo->curso->titulo }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mis-cursos', $material->modulo->curso) }}">Curso</a></li>
                    <li class="breadcrumb-item active">{{ $material->titulo }}</li>
                </ol>
            </nav>

            <h4 class="font-weight-bold">{{ $material->titulo }}</h4>

            @if($material->tipo === 'video' && $material->getEmbedUrlAttribute())
            <div class="embed-responsive embed-responsive-16by9 mt-3">
                <iframe class="embed-responsive-item" src="{{ $material->getEmbedUrlAttribute() }}" allowfullscreen></iframe>
            </div>
            @elseif($material->tipo === 'pdf')
            <embed src="{{ $material->archivo ? asset('storage/' . $material->archivo) : $material->url }}" type="application/pdf" width="100%" height="600px" class="mt-3">
            @elseif($material->tipo === 'cuestionario')
                @include('cursos._cuestionario', ['material' => $material])
            @endif
        </div>
    </div>
</div>
@endsection
