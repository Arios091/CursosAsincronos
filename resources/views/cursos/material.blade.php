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
                @php
                    $filename = basename($material->archivo ?? $material->url);
                    $pdfUrl = $material->archivo ? route('archivo.pdf', $filename) : $material->url;
                @endphp
                <div id="pdf-viewer-material" class="pdf-viewer"
                     style="width: 100%; height: 600px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px; background: #525659; margin-top: 1rem;"
                     data-pdf-url="{{ $pdfUrl }}">
                    <div class="pdf-loading text-center py-5" style="color:#fff;">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Cargando PDF...
                    </div>
                </div>
                @if($material->archivo)
                <a href="{{ route('archivo.pdf.descargar', $filename) }}" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">
                    <i class="fas fa-download mr-1"></i> Descargar PDF
                </a>
                @endif
            @elseif($material->tipo === 'cuestionario')
                @include('cursos._cuestionario', ['material' => $material])
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($material->tipo === 'pdf')
<script>
    (function() {
        var viewer = document.getElementById('pdf-viewer-material');
        if (!viewer) return;
        var pdfUrl = viewer.getAttribute('data-pdf-url');

        var PDFJS_CDN_LIB  = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
        var PDFJS_CDN_WORKER = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        var PDFJS_LOCAL_LIB   = '{{ route('pdfjs.asset', 'pdf.min.js') }}';
        var PDFJS_LOCAL_WORKER = '{{ route('pdfjs.asset', 'pdf.worker.min.js') }}';

        function loadPdfJs(src, ok) {
            if (document.querySelector('script[src="' + src + '"]')) { ok(); return; }
            var s = document.createElement('script');
            s.src = src;
            s.onload = ok;
            s.onerror = function() { console.error('[PDF] No cargo pdf.js de:', src); ok(); };
            document.head.appendChild(s);
        }

        function startViewer() {
            var pdfjsLib = window['pdfjs-dist/build/pdf'] || window.pdfjsLib;
            if (!pdfjsLib) {
                viewer.querySelector('.pdf-loading').innerHTML = 'No se pudo cargar el visor PDF. Usa "Descargar PDF".';
                return;
            }

            var cdnOk = document.querySelector('script[src="' + PDFJS_CDN_LIB + '"]');
            pdfjsLib.GlobalWorkerOptions.workerSrc = cdnOk ? PDFJS_CDN_WORKER : PDFJS_LOCAL_WORKER;

            var pdfTimeout = setTimeout(function() {
                viewer.querySelector('.pdf-loading').innerHTML = 'El visor PDF tardo demasiado. Usa "Descargar PDF".';
            }, 30000);

            pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
                clearTimeout(pdfTimeout);
                viewer.querySelector('.pdf-loading')?.remove();
                var scale = 1.2;
                function renderPage(n) {
                    return pdf.getPage(n).then(function(page) {
                        var viewport = page.getViewport({ scale: scale });
                        var canvas = document.createElement('canvas');
                        canvas.style.display = 'block';
                        canvas.style.margin = '0 auto 8px auto';
                        canvas.style.boxShadow = '0 2px 8px rgba(0,0,0,0.4)';
                        canvas.style.background = '#fff';
                        canvas.width = viewport.width;
                        canvas.height = viewport.height;
                        return page.render({ canvasContext: canvas.getContext('2d'), viewport: viewport }).promise.then(function() {
                            viewer.appendChild(canvas);
                        });
                    });
                }
                var p = Promise.resolve();
                for (var i = 1; i <= pdf.numPages; i++) {
                    (function(n){ p = p.then(function(){ return renderPage(n); }); })(i);
                }
            }).catch(function() {
                clearTimeout(pdfTimeout);
                viewer.querySelector('.pdf-loading').innerHTML = 'Error al cargar el PDF. Usa "Descargar PDF".';
            });
        }

        loadPdfJs(PDFJS_CDN_LIB, startViewer);
    })();
</script>
@endif
@endpush
