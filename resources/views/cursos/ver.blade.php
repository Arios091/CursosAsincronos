@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 p-0" style="background: #fff; border-right: 1px solid #e5e7eb; min-height: calc(100vh - 56px);">
            <div style="background: linear-gradient(135deg, #0B5E2E, #0A4A24); padding: 1.5rem; color: #fff;">
                <h6 class="font-weight-bold">{{ $curso->titulo }}</h6>
                <div class="progress mt-2" style="height: 6px; background: rgba(255,255,255,0.3);">
                    <div class="progress-bar" style="width: {{ $progreso }}%; background: #C9A227; transition: width 0.3s;" id="progressBar"></div>
                </div>
                <small class="mt-1 d-block">{{ $progreso }}% completado ({{ $modulosCompletados }}/{{ $totalModulos }} modulos)</small>
            </div>
            <div style="max-height: calc(100vh - 200px); overflow-y: auto;">
                @foreach($modulos as $modIdx => $modulo)
                    @php
                        $materialesMod = $modulo->materiales->sortBy('orden');
                        $completadosMod = $materialesMod->filter(fn($m) => in_array($m->id, $materialesCompletados))->count();
                        $tieneQuiz = $modulo->cuestionario !== null;
                        $quizAprobado = $tieneQuiz && isset($resultadosCuestionarios[$modulo->cuestionario->id]);
                        $moduloCompleto = $completadosMod === $materialesMod->count() && (!$tieneQuiz || $quizAprobado);
                        $esActual = $moduloActual && $modulo->id === $moduloActual->id;
                    @endphp
                    <div class="p-3 border-bottom {{ $moduloCompleto ? 'bg-light' : ($esActual ? 'bg-white' : '') }}">
                        <div class="d-flex align-items-center">
                            @if($moduloCompleto)
                                <i class="fas fa-check-circle text-success mr-2"></i>
                            @elseif($esActual)
                                <i class="fas fa-play-circle mr-2" style="color: #2563eb;"></i>
                            @else
                                <i class="fas fa-lock text-muted mr-2"></i>
                            @endif
                            <strong class="small {{ $moduloCompleto ? 'text-muted' : ($esActual ? 'text-dark' : 'text-muted') }}">
                                {{ $modulo->titulo }}
                            </strong>
                        </div>
                        <div class="ml-4 mt-1">
                            <small class="text-muted">
                                @if($tieneQuiz)
                                    {{ $completadosMod }}/{{ $materialesMod->count() }} materiales
                                    @if($quizAprobado)
                                        <span class="text-success ml-1"><i class="fas fa-check-circle"></i> Quiz aprobado</span>
                                    @elseif($moduloCompleto && !$quizAprobado)
                                        <span class="text-warning ml-1">Quiz pendiente</span>
                                    @endif
                                @else
                                    {{ $completadosMod }}/{{ $materialesMod->count() }} materiales
                                @endif
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-9 p-4" style="background: #f3f4f6; min-height: calc(100vh - 56px);">
            @if($examenFinalAprobado)
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-trophy fa-5x mb-3" style="color: #C9A227;"></i>
                        <h4 class="font-weight-bold" style="color: #0B5E2E;">Felicidades!</h4>
                        <p class="lead">Has completado exitosamente el curso <strong>{{ $curso->titulo }}</strong>.</p>
                        <p class="text-muted">Puntaje final: {{ $resultadoExamenFinal->puntaje }}%</p>
                        <div class="mt-4">
                            <a href="{{ route('certificado.ver', $curso) }}" class="btn text-white font-weight-bold px-4 mr-2" style="background: #0B5E2E;">
                                <i class="fas fa-certificate mr-1"></i> Ver Certificado
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary font-weight-bold px-4">
                                <i class="fas fa-home mr-1"></i> Ir al Inicio
                            </a>
                        </div>
                    </div>
                </div>

            @elseif($mostrarExamenFinal)
                <div class="card shadow-sm">
                    <div class="card-header font-weight-bold" style="background: linear-gradient(135deg, #0B5E2E, #0A4A24); color: #fff;">
                        <i class="fas fa-file-alt mr-1"></i> {{ $curso->examenFinal->titulo ?? 'Examen Final' }}
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Responde todas las preguntas correctamente. Minimo para aprobar: {{ $curso->examenFinal->min_aprobacion ?? 80 }}%</p>
                        <div id="examen-final-container">
                            <form id="examenFinalForm">
                                @csrf
                                @foreach($curso->examenFinal->preguntas->sortBy('orden') as $pIdx => $pregunta)
                                <div class="mb-4 p-3 border rounded">
                                    <p class="font-weight-bold mb-2">{{ ($pIdx + 1) }}. {{ $pregunta->texto }}</p>
                                    @foreach($pregunta->opciones->sortBy('orden') as $opcion)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="radio" name="respuestas[{{ $pregunta->id }}]" value="{{ $opcion->id }}" id="ef-{{ $opcion->id }}" required>
                                        <label class="form-check-label" for="ef-{{ $opcion->id }}">{{ $opcion->texto }}</label>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                                <button type="submit" class="btn text-white font-weight-bold px-4" style="background: #0B5E2E;" id="btnEnviarExamen">
                                    <i class="fas fa-paper-plane mr-1"></i> Enviar Examen Final
                                </button>
                            </form>
                            <div id="examen-resultado" class="mt-3" style="display:none;"></div>
                        </div>
                    </div>
                </div>

            @elseif($moduloActual && $materialActual)
                @php $completado = in_array($materialActual->id, $materialesCompletados); @endphp
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="font-weight-bold mb-0">
                                <span class="badge badge-secondary mr-2">{{ $moduloActual->titulo }}</span>
                                {{ $materialActual->titulo }}
                            </h5>
                            @if($completado)
                                <span class="badge badge-success p-2"><i class="fas fa-check mr-1"></i> Completado</span>
                            @endif
                        </div>

                        @if($materialActual->tipo === 'video')
                            @php $embedUrl = $materialActual->getEmbedUrlAttribute(); @endphp
                            @if($embedUrl)
                                @if($materialActual->esYouTube())
                                    <div id="youtube-player-{{ $materialActual->id }}" class="embed-responsive embed-responsive-16by9 mb-3"></div>
                                @elseif($materialActual->esVimeo())
                                    <div id="vimeo-player-{{ $materialActual->id }}" class="embed-responsive embed-responsive-16by9 mb-3"></div>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        El video se detectará automáticamente al finalizar. Si no se habilita, haz clic en "Continuar" manualmente.
                                    </div>
                                @elseif($materialActual->esGoogleDrive())
                                    <div class="embed-responsive embed-responsive-16by9 mb-3">
                                        <iframe class="embed-responsive-item" src="{{ $embedUrl }}" allowfullscreen></iframe>
                                    </div>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        El video se detectará automáticamente. Si no se habilita, haz clic en "Continuar" manualmente.
                                    </div>
                                @else
                                    <div class="embed-responsive embed-responsive-16by9 mb-3">
                                        <iframe class="embed-responsive-item" src="{{ $embedUrl }}" allowfullscreen></iframe>
                                    </div>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Haz clic en "Continuar" cuando hayas terminado de ver el video.
                                    </div>
                                @endif
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light p-5 mb-3">
                                    <p class="text-muted">Video no disponible</p>
                                </div>
                            @endif

                        @elseif($materialActual->tipo === 'pdf')
                            <div id="pdf-viewer-{{ $materialActual->id }}" class="mb-3" style="min-height: 300px;">
                                <div class="text-center py-5">
                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                    <p class="mt-2">Cargando PDF...</p>
                                </div>
                            </div>

                        @elseif($materialActual->tipo === 'cuestionario')
                            <div class="alert alert-warning">Cuestionario tipo material legacy.</div>
                        @endif

                        @if(!$completado)
                        <button class="btn text-white font-weight-bold px-4 mt-2" style="background: #0B5E2E;" id="btnContinuar" disabled
                                data-material="{{ $materialActual->id }}"
                                data-tipo="{{ $materialActual->tipo }}"
                                data-es-youtube="{{ $materialActual->esYouTube() ? '1' : '0' }}">
                            <i class="fas fa-arrow-right mr-1"></i> Continuar
                        </button>
                        <div id="material-message" class="mt-2"></div>
                        @endif
                    </div>
                </div>

            @elseif($moduloActual && !$materialActual && $moduloActual->cuestionario)
                @php $cuestionario = $moduloActual->cuestionario; @endphp
                <div class="card shadow-sm">
                    <div class="card-header font-weight-bold" style="background: linear-gradient(135deg, #0B5E2E, #0A4A24); color: #fff;">
                        <i class="fas fa-question-circle mr-1"></i> Cuestionario: {{ $cuestionario->titulo }}
                    </div>
                    <div class="card-body">
                        @php $yaAprobado = isset($resultadosCuestionarios[$cuestionario->id]); @endphp
                        @if($yaAprobado)
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle mr-1"></i> Cuestionario aprobado.
                            </div>
                        @else
                            <p class="text-muted mb-3">Responde todas las preguntas correctamente ({{ $cuestionario->min_aprobacion }}% minimo).</p>
                            <form id="cuestionario-modulo-form" data-modulo="{{ $moduloActual->id }}">
                                @csrf
                                @foreach($cuestionario->preguntas as $pIdx => $pregunta)
                                <div class="mb-4 p-3 border rounded">
                                    <p class="font-weight-bold mb-2">{{ ($pIdx + 1) }}. {{ $pregunta->texto }}</p>
                                    @foreach($pregunta->opciones as $opcion)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="radio" name="respuestas[{{ $pregunta->id }}]" value="{{ $opcion->id }}" id="qp-{{ $opcion->id }}" required>
                                        <label class="form-check-label" for="qp-{{ $opcion->id }}">{{ $opcion->texto }}</label>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                                <button type="submit" class="btn text-white font-weight-bold px-4" style="background: #0B5E2E;">
                                    <i class="fas fa-paper-plane mr-1"></i> Enviar Respuestas
                                </button>
                            </form>
                            <div id="cuestionario-resultado" class="mt-3" style="display:none;"></div>
                        @endif
                    </div>
                </div>

            @elseif($modulosCompletados === $totalModulos && $totalModulos > 0 && !$curso->examenFinal)
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h5 class="font-weight-bold">Has completado todos los modulos</h5>
                        <p class="text-muted">Este curso no tiene examen final. Completa todos los materiales para culminarlo.</p>
                        <a href="{{ route('mis-cursos', $curso) }}" class="btn text-white px-4" style="background: #0B5E2E;">
                            <i class="fas fa-sync mr-1"></i> Recargar
                        </a>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-book-open fa-4x mb-3" style="color: #0B5E2E;"></i>
                        <h5 class="font-weight-bold">Bienvenido al curso</h5>
                        <p class="text-muted">Completa los modulos para empezar.</p>
                        <a href="{{ route('mis-cursos', $curso) }}" class="btn text-white px-4" style="background: #0B5E2E;">
                            <i class="fas fa-sync mr-1"></i> Recargar
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($moduloActual && $materialActual && $materialActual->tipo === 'pdf' && !$completado)
<script src="{{ asset('js/pdfjs/pdf.min.js') }}"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ asset('js/pdfjs/pdf.worker.min.js') }}';

    document.addEventListener('DOMContentLoaded', function() {
        var pdfUrl = '{{ $materialActual->archivo ? storage_url($materialActual->archivo) : $materialActual->url }}';
        var container = document.getElementById('pdf-viewer-{{ $materialActual->id }}');

        if (!container) {
            console.error('[PDF] Container not found: pdf-viewer-{{ $materialActual->id }}');
            habilitarContinuar();
            return;
        }

        // Show loading state
        container.innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Cargando PDF...</p></div>';

        pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
            container.innerHTML = '';
            var wrapper = document.createElement('div');
            wrapper.style.maxHeight = '500px';
            wrapper.style.overflowY = 'auto';
            wrapper.style.border = '1px solid #de2e6';
            wrapper.style.borderRadius = '4px';
            container.appendChild(wrapper);

            var totalPages = pdf.numPages;
            var paginaActual = 1;

            function renderSiguientePagina() {
                if (paginaActual > totalPages) {
                    wrapper.addEventListener('scroll', function onScroll() {
                        var diff = wrapper.scrollHeight - wrapper.scrollTop - wrapper.clientHeight;
                        if (diff < 50) {
                            habilitarContinuar();
                            wrapper.removeEventListener('scroll', onScroll);
                        }
                    });
                    return;
                }
                var num = paginaActual++;
                pdf.getPage(num).then(function(page) {
                    var viewport = page.getViewport({ scale: 1.0 });
                    var canvas = document.createElement('canvas');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    canvas.style.width = '100%';
                    canvas.style.height = 'auto';
                    canvas.style.display = 'block';
                    wrapper.appendChild(canvas);
                    return page.render({
                        canvasContext: canvas.getContext('2d'),
                        viewport: viewport
                    }).promise;
                }).then(function() {
                    renderSiguientePagina();
                }).catch(function(err) {
                    console.error('Error rendering page:', err);
                    renderSiguientePagina();
                });
            }

            renderSiguientePagina();
        }).catch(function(err) {
            console.error('Error loading PDF:', err);
            container.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle mr-1"></i>Error al cargar el PDF. <a href="' + pdfUrl + '" target="_blank">Abrir en nueva pestaña</a></div>';
            habilitarContinuar();
        });
    });
</script>
@endif
<script>
    // ============================================
    // DETECCIÓN DE FINALIZACIÓN DE VIDEOS
    // ============================================
    var materialActual = null;
    var playerInstances = {};

    @if($moduloActual && $materialActual && !$completado)
        materialActual = {
            id: {{ $materialActual->id }},
            tipo: '{{ $materialActual->tipo }}',
            esYouTube: {{ $materialActual->esYouTube() ? 'true' : 'false' }},
            esVimeo: {{ $materialActual->esVimeo() ? 'true' : 'false' }},
            esDrive: {{ $materialActual->esGoogleDrive() ? 'true' : 'false' }},
        };

        @if($materialActual->tipo === 'video' && $materialActual->esYouTube())
            // YouTube IFrame API
            var tag = document.createElement('script');
            tag.src = 'https://www.youtube.com/iframe_api';
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            window.onYouTubeIframeAPIReady = function() {
                playerInstances.youtube = new YT.Player('youtube-player-{{ $materialActual->id }}', {
                    height: '400',
                    width: '100%',
                    videoId: '{{ $materialActual->getYouTubeId() }}',
                    playerVars: {
                        'rel': 0,
                        'modestbranding': 1,
                        'playsinline': 1
                    },
                    events: {
                        'onReady': function(event) {
                            console.log('[YouTube] Player ready');
                            // Ensure we catch ended even if it fires before listener
                        },
                        'onStateChange': function(event) {
                            console.log('[YouTube] State:', event.data);
                            if (event.data === YT.PlayerState.ENDED) {
                                console.log('[YouTube] Video ended');
                                habilitarContinuar();
                            }
                            // Fallback: also check near-end via progress
                            if (event.data === YT.PlayerState.PLAYING) {
                                var checkProgress = setInterval(function() {
                                    try {
                                        var current = playerInstances.youtube.getCurrentTime();
                                        var duration = playerInstances.youtube.getDuration();
                                        if (duration > 0 && current / duration >= 0.95) {
                                            console.log('[YouTube] 95% reached');
                                            habilitarContinuar();
                                            clearInterval(checkProgress);
                                        }
                                    } catch(e) { clearInterval(checkProgress); }
                                }, 2000);
                            }
                        },
                        'onError': function(event) {
                            console.warn('[YouTube] Error:', event.data);
                            // Enable manual fallback on error
                            habilitarContinuar();
                        }
                    }
                });
            };
        @endif

        @if($materialActual->tipo === 'video' && $materialActual->esVimeo())
            // Vimeo Player API
            var vimeoScript = document.createElement('script');
            vimeoScript.src = 'https://player.vimeo.com/api/player.js';
            document.head.appendChild(vimeoScript);

            vimeoScript.onload = function() {
                var iframe = document.querySelector('#vimeo-player-{{ $materialActual->id }}');
                if (iframe) {
                    playerInstances.vimeo = new Vimeo.Player(iframe);
                    playerInstances.vimeo.on('ended', function() {
                        console.log('[Vimeo] Video ended');
                        habilitarContinuar();
                    }).catch(function(err) {
                        console.warn('[Vimeo] ended event error:', err);
                    });
                    // Fallback progress
                    playerInstances.vimeo.on('timeupdate', function(data) {
                        if (data.percent >= 0.95) {
                            console.log('[Vimeo] 95% reached');
                            habilitarContinuar();
                        }
                    });
                }
            };
        @endif

        @if($materialActual->tipo === 'video' && $materialActual->esGoogleDrive())
            // Google Drive - no API for progress, enable manual after reasonable time
            // Or show manual button immediately
            console.log('[Drive] Google Drive video - manual completion');
            setTimeout(habilitarContinuar, 10000); // 10s fallback
        @endif

        @if($materialActual->tipo === 'video' && !$materialActual->esYouTube() && !$materialActual->esVimeo() && !$materialActual->esGoogleDrive())
            // Generic video/iframe - timer fallback
            console.log('[Video] Generic video - timer fallback');
            setTimeout(habilitarContinuar, 30000); // 30s fallback
        @endif
    @endif

    function habilitarContinuar() {
        var btn = document.getElementById('btnContinuar');
        if (btn && btn.disabled) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-arrow-right mr-1"></i> Continuar';
            console.log('[UI] Continuar habilitado para material:', materialActual?.id);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var btnContinuar = document.getElementById('btnContinuar');
        if (btnContinuar) {
            btnContinuar.addEventListener('click', function() {
                var btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Procesando...';

                fetch('/cursos/material/' + btn.dataset.material + '/completar', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.mostrarExamenFinal) {
                        location.reload();
                    } else if (data.siguiente) {
                        location.reload();
                    } else if (data.siguienteEsCuestionario) {
                        location.reload();
                    } else {
                        location.reload();
                    }
                })
                .catch(function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-arrow-right mr-1"></i> Continuar';
                    document.getElementById('material-message').innerHTML = '<div class="alert alert-danger">Error al procesar. Intenta de nuevo.</div>';
                });
            });
        }

        // Module quiz form
        var quizForm = document.getElementById('cuestionario-modulo-form');
        if (quizForm) {
            quizForm.addEventListener('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var moduloId = this.dataset.modulo;

                fetch('/cursos/modulo/' + moduloId + '/cuestionario', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    var div = document.getElementById('cuestionario-resultado');
                    div.style.display = 'block';
                    if (data.aprobado) {
                        div.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle mr-1"></i> Cuestionario aprobado! Puntaje: ' + data.puntaje + '%</div>';
                        setTimeout(function() { location.reload(); }, 2000);
                    } else {
                        div.innerHTML = '<div class="alert alert-danger"><i class="fas fa-times-circle mr-1"></i> Cuestionario no aprobado. Puntaje: ' + data.puntaje + '%. Minimo: ' + '{{ $moduloActual && $moduloActual->cuestionario ? $moduloActual->cuestionario->min_aprobacion : 100 }}' + '%</div>';
                        setTimeout(function() { location.reload(); }, 3000);
                    }
                });
            });
        }

        // Final exam form
        var examenForm = document.getElementById('examenFinalForm');
        if (examenForm) {
            examenForm.addEventListener('submit', function(e) {
                e.preventDefault();
                var btn = document.getElementById('btnEnviarExamen');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Enviando...';

                var formData = new FormData(this);

                fetch('{{ route("cursos.examen-final", $curso) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.aprobado) {
                        window.location.href = data.redirect;
                    } else {
                        var div = document.getElementById('examen-resultado');
                        div.style.display = 'block';
                        div.innerHTML = '<div class="alert alert-danger"><i class="fas fa-times-circle mr-1"></i> Examen no aprobado. Puntaje: ' + data.puntaje + '% (minimo ' + data.minimo + '%). Aciertos: ' + data.aciertos + '/' + data.total + '.</div>';
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-paper-plane mr-1"></i> Enviar Examen Final';
                    }
                })
                .catch(function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane mr-1"></i> Enviar Examen Final';
                });
            });
        }
    });
</script>
