<div>
    <div class="container py-4">
        <div class="card shadow">
            <div class="card-header text-white font-weight-bold d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #0B5E2E, #0A4A24);">
                <span><i class="fas fa-plus-circle mr-1"></i> Crear Nuevo Curso</span>
                <span class="badge badge-light px-3 py-1">Paso {{ $paso }} de 3</span>
            </div>
            <div class="card-body">
                <div class="progress mb-4" style="height: 6px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ ($paso / 3) * 100 }}%; background: #C9A227; transition: width 0.3s ease;" aria-valuenow="{{ $paso }}" aria-valuemin="1" aria-valuemax="3"></div>
                </div>

                <div class="d-flex justify-content-center mb-4">
                    <div class="step-indicator d-flex align-items-center">
                        <div class="step-circle {{ $paso >= 1 ? 'active' : '' }}">1</div>
                        <div class="step-label {{ $paso >= 1 ? 'text-dark font-weight-bold' : 'text-muted' }} ml-2 mr-3">Informacion</div>
                        <div class="step-line {{ $paso > 1 ? 'completed' : '' }}"></div>
                        <div class="step-circle {{ $paso >= 2 ? 'active' : '' }} ml-3">2</div>
                        <div class="step-label {{ $paso >= 2 ? 'text-dark font-weight-bold' : 'text-muted' }} ml-2 mr-3">Modulos</div>
                        <div class="step-line {{ $paso > 2 ? 'completed' : '' }}"></div>
                        <div class="step-circle {{ $paso >= 3 ? 'active' : '' }} ml-3">3</div>
                        <div class="step-label {{ $paso >= 3 ? 'text-dark font-weight-bold' : 'text-muted' }} ml-2">Examen Final</div>
                    </div>
                </div>

                <style>
                    .step-circle {
                        width: 32px; height: 32px; border-radius: 50%;
                        background: #e9ecef; color: #6c757d;
                        display: flex; align-items: center; justify-content: center;
                        font-size: 0.85rem; font-weight: 700;
                        transition: all 0.3s;
                    }
                    .step-circle.active {
                        background: #0B5E2E; color: #fff;
                    }
                    .step-line {
                        width: 40px; height: 3px; background: #e9ecef;
                        transition: background 0.3s;
                    }
                    .step-line.completed {
                        background: #0B5E2E;
                    }
                </style>

                @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                @endif

                @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                @endif

                {{-- Paso 1: Informacion General --}}
                @if($paso === 1)
                <div>
                    <h5 class="font-weight-bold mb-4" style="color: #0B5E2E;">
                        <i class="fas fa-info-circle mr-1"></i> Informacion General
                    </h5>

                    <div class="form-group">
                        <label class="font-weight-bold">Titulo del Curso</label>
                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" wire:model="titulo" placeholder="Ej: Introduccion a la Docencia Universitaria">
                        @error('titulo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Descripcion</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" wire:model="descripcion" rows="4" placeholder="Describe el contenido del curso..."></textarea>
                        @error('descripcion') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Audiencia</label>
                        <select class="form-control" wire:model="audiencia">
                            <option value="docente">Docentes</option>
                            <option value="estudiante">Estudiantes</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Duracion (horas)</label>
                        <input type="number" class="form-control @error('horas') is-invalid @enderror" wire:model="horas" placeholder="Ej: 40" min="1">
                        @error('horas') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Imagen del Curso</label>
                        <small class="text-muted d-block mb-1">Opcional. Tamaño maximo: 2MB</small>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" wire:model="imagen" accept="image/*" id="imagenCurso">
                            <label class="custom-file-label" for="imagenCurso">{{ $imagen && is_object($imagen) ? $imagen->getClientOriginalName() : ($imagen ?: 'Seleccionar imagen...') }}</label>
                        </div>
                        @error('imagen') <span class="text-danger small">{{ $message }}</span> @enderror
                        @if($imagen)
                        <div class="mt-2 p-3 border rounded text-center bg-light">
                            <i class="fas fa-image fa-3x text-muted"></i>
                            <p class="small text-muted mt-1 mb-0">{{ is_object($imagen) ? $imagen->getClientOriginalName() : $imagen }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Paso 2: Modulos --}}
                @if($paso === 2)
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="font-weight-bold mb-0" style="color: #0B5E2E;">
                            <i class="fas fa-layer-group mr-1"></i> Modulos y Materiales
                        </h5>
                        <button class="btn btn-sm text-white font-weight-bold" style="background: #0B5E2E;" wire:click="addModulo">
                            <i class="fas fa-plus"></i> Agregar Modulo
                        </button>
                    </div>

                    @if(count($modulos) === 0)
                    <div class="text-center py-5">
                        <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay modulos aun. Haz clic en "Agregar Modulo" para empezar.</p>
                    </div>
                    @endif

                    @foreach($modulos as $modIdx => $modulo)
                    <div class="card mb-3 border-{{ $modIdx % 2 === 0 ? 'success' : 'warning' }}">
                        <div class="card-header d-flex justify-content-between align-items-center" style="background: #f8f9fa;">
                            <strong><i class="fas fa-cube mr-1" style="color: #0B5E2E;"></i> Modulo {{ $modIdx + 1 }}</strong>
                            <button class="btn btn-sm btn-outline-danger" type="button" onclick="showConfirm('Eliminar este modulo y todo su contenido?', function() { @this.call('removeModulo', {{ $modIdx }}); });">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Titulo del Modulo</label>
                                        <input type="text" class="form-control form-control-sm" wire:model="modulos.{{ $modIdx }}.titulo" placeholder="Ej: Fundamentos Teoricos">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Descripcion</label>
                                        <input type="text" class="form-control form-control-sm" wire:model="modulos.{{ $modIdx }}.descripcion" placeholder="Breve descripcion del modulo">
                                    </div>
                                </div>
                            </div>

                            {{-- Materiales de Aprendizaje --}}
                            <div class="mt-3 p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="small" style="color: #0B5E2E;">
                                        <i class="fas fa-book mr-1"></i> Materiales de Aprendizaje
                                    </strong>
                                    <button class="btn btn-sm btn-outline-success" wire:click="addMaterial({{ $modIdx }})">
                                        <i class="fas fa-plus"></i> Agregar Material
                                    </button>
                                </div>

                                @if(count($modulo['materiales']) === 0)
                                <p class="text-muted small mb-0">Sin materiales. Agrega al menos un video o PDF.</p>
                                @endif

                                @foreach($modulo['materiales'] as $matIdx => $material)
                                <div class="border rounded p-2 mb-2 bg-white">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong class="small">
                                            <i class="fas fa-{{ $material['tipo'] === 'video' ? 'video' : 'file-pdf' }} mr-1" style="color: {{ $material['tipo'] === 'video' ? '#0d6efd' : '#dc3545' }};"></i>
                                            Material {{ $matIdx + 1 }}
                                        </strong>
                                        <button class="btn btn-sm btn-outline-danger py-0 px-1" wire:click="removeMaterial({{ $modIdx }}, {{ $matIdx }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control form-control-sm" wire:model="modulos.{{ $modIdx }}.materiales.{{ $matIdx }}.titulo" placeholder="Titulo del material">
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control form-control-sm" wire:model="modulos.{{ $modIdx }}.materiales.{{ $matIdx }}.tipo">
                                                <option value="video">Video</option>
                                                <option value="pdf">PDF</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            @if($material['tipo'] === 'video')
                                            <input type="text" class="form-control form-control-sm" wire:model="modulos.{{ $modIdx }}.materiales.{{ $matIdx }}.url" placeholder="URL de YouTube">
                                            @else
                                            <input type="file" class="form-control-file form-control-sm" wire:model="modulos.{{ $modIdx }}.materiales.{{ $matIdx }}.archivo" accept=".pdf">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Cuestionario del Modulo --}}
                            <div class="mt-3 p-3 border rounded" style="border-left: 4px solid #C9A227;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong style="color: #C9A227;">
                                        <i class="fas fa-question-circle mr-1"></i> Cuestionario del Modulo
                                    </strong>
                                    <button class="btn btn-sm btn-outline-primary" wire:click="addPreguntaCuestionario({{ $modIdx }})">
                                        <i class="fas fa-plus"></i> Agregar Pregunta
                                    </button>
                                </div>
                                <small class="text-muted d-block mb-2">Aprobacion requerida: <strong>100%</strong> (todas correctas para pasar al siguiente modulo)</small>

                                @if(count($modulo['cuestionario']['preguntas']) === 0)
                                <p class="text-muted small mb-0">Sin preguntas. Agrega al menos una pregunta al cuestionario.</p>
                                @endif

                                @foreach($modulo['cuestionario']['preguntas'] as $pregIdx => $pregunta)
                                <div class="border rounded p-3 mb-2 bg-white">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong class="small">Pregunta {{ $pregIdx + 1 }}</strong>
                                        <button class="btn btn-sm btn-outline-danger py-0 px-1" wire:click="removePreguntaCuestionario({{ $modIdx }}, {{ $pregIdx }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" wire:model="modulos.{{ $modIdx }}.cuestionario.preguntas.{{ $pregIdx }}.texto" placeholder="Escribe la pregunta...">
                                    </div>
                                    <div class="ml-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="font-weight-bold text-muted">Opciones:</small>
                                            <button class="btn btn-sm btn-outline-secondary py-0 px-1" wire:click="addOpcionCuestionario({{ $modIdx }}, {{ $pregIdx }})">
                                                <i class="fas fa-plus"></i> Opcion
                                            </button>
                                        </div>
                                        @foreach($pregunta['opciones'] as $opcIdx => $opcion)
                                        <div class="input-group input-group-sm mt-1">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-white">
                                                    <input type="radio" name="correcta_q_{{ $modIdx }}_{{ $pregIdx }}"
                                                           wire:click="setCorrectaCuestionario({{ $modIdx }}, {{ $pregIdx }}, {{ $opcIdx }})"
                                                           {{ $opcion['es_correcta'] ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" wire:model="modulos.{{ $modIdx }}.cuestionario.preguntas.{{ $pregIdx }}.opciones.{{ $opcIdx }}.texto" placeholder="Opcion {{ $opcIdx + 1 }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-danger" wire:click="removeOpcionCuestionario({{ $modIdx }}, {{ $pregIdx }}, {{ $opcIdx }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Paso 3: Examen Final --}}
                @if($paso === 3)
                <div>
                    <h5 class="font-weight-bold mb-4" style="color: #0B5E2E;">
                        <i class="fas fa-graduation-cap mr-1"></i> Examen Final del Curso
                    </h5>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i>
                        El examen final requiere <strong>80% de aprobacion</strong> para completar el curso y obtener el certificado.
                        Solo estara disponible despues de aprobar todos los cuestionarios de modulo.
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Titulo del Examen</label>
                        <input type="text" class="form-control" wire:model="examenFinal.titulo" placeholder="Examen Final">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong style="color: #0B5E2E;"><i class="fas fa-list mr-1"></i> Preguntas del Examen Final</strong>
                        <button class="btn btn-sm text-white font-weight-bold" style="background: #C9A227;" wire:click="addPreguntaExamen">
                            <i class="fas fa-plus"></i> Agregar Pregunta
                        </button>
                    </div>

                    @if(count($examenFinal['preguntas']) === 0)
                    <div class="text-center py-4">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Agrega las preguntas para el examen final del curso.</p>
                    </div>
                    @endif

                    @foreach($examenFinal['preguntas'] as $pregIdx => $pregunta)
                    <div class="card mb-3 border-warning">
                        <div class="card-header d-flex justify-content-between align-items-center" style="background: #fff8e1;">
                            <strong><i class="fas fa-question-circle mr-1" style="color: #C9A227;"></i> Pregunta {{ $pregIdx + 1 }}</strong>
                            <button class="btn btn-sm btn-outline-danger" wire:click="removePreguntaExamen({{ $pregIdx }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="small font-weight-bold">Texto de la Pregunta</label>
                                <input type="text" class="form-control" wire:model="examenFinal.preguntas.{{ $pregIdx }}.texto" placeholder="Escribe la pregunta...">
                            </div>
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="font-weight-bold text-muted">Alternativas:</small>
                                    <button class="btn btn-sm btn-outline-secondary" wire:click="addOpcionExamen({{ $pregIdx }})">
                                        <i class="fas fa-plus"></i> Alternativa
                                    </button>
                                </div>
                                @foreach($pregunta['opciones'] as $opcIdx => $opcion)
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-white">
                                            <input type="radio" name="correcta_e_{{ $pregIdx }}"
                                                   wire:click="setCorrectaExamen({{ $pregIdx }}, {{ $opcIdx }})"
                                                   {{ $opcion['es_correcta'] ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" wire:model="examenFinal.preguntas.{{ $pregIdx }}.opciones.{{ $opcIdx }}.texto" placeholder="Alternativa {{ chr(65 + $opcIdx) }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-danger" wire:click="removeOpcionExamen({{ $pregIdx }}, {{ $opcIdx }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach

                    {{-- Resumen del Curso Completo --}}
                    <div class="mt-4">
                        <h5 class="font-weight-bold mb-3" style="color: #0B5E2E;">
                            <i class="fas fa-eye mr-1"></i> Resumen del Curso
                        </h5>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="font-weight-bold">{{ $titulo ?: '(Sin titulo)' }}</h5>
                                <p class="text-muted">{{ $descripcion ?: '(Sin descripcion)' }}</p>
                                <p><strong>Audiencia:</strong> {{ $audiencia === 'docente' ? 'Docentes' : 'Estudiantes' }} &middot; <strong>Duracion:</strong> {{ $horas ?: 0 }} horas</p>

                                <h6 class="font-weight-bold mt-4" style="color: #0B5E2E;">Modulos ({{ count($modulos) }})</h6>
                                @foreach($modulos as $modIdx => $modulo)
                                <div class="border-left border-success pl-3 mb-3">
                                    <strong>Modulo {{ $modIdx + 1 }}: {{ $modulo['titulo'] ?: '(Sin titulo)' }}</strong>
                                    <ul class="list-unstyled ml-3 mt-1 small">
                                        @foreach($modulo['materiales'] as $material)
                                        <li>
                                            <i class="fas fa-{{ $material['tipo'] === 'video' ? 'video' : 'file-pdf' }} mr-1" style="color: #0B5E2E;"></i>
                                            {{ $material['titulo'] ?: '(Sin titulo)' }}
                                            <span class="badge badge-{{ $material['tipo'] === 'video' ? 'primary' : 'danger' }}">{{ strtoupper($material['tipo']) }}</span>
                                        </li>
                                        @endforeach
                                        @if(count($modulo['cuestionario']['preguntas']) > 0)
                                        <li>
                                            <i class="fas fa-question-circle mr-1" style="color: #C9A227;"></i>
                                            Cuestionario: {{ count($modulo['cuestionario']['preguntas']) }} pregunta(s)
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                                @endforeach

                                <h6 class="font-weight-bold mt-3" style="color: #C9A227;">Examen Final</h6>
                                <p class="small">
                                    <i class="fas fa-file-alt mr-1"></i>
                                    {{ count($examenFinal['preguntas']) }} pregunta(s) &middot; 80% para aprobar
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Navegacion --}}
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    @if($paso > 1)
                    <button class="btn btn-outline-secondary" wire:click="pasoAnterior">
                        <i class="fas fa-arrow-left"></i> Anterior
                    </button>
                    @else
                    <div></div>
                    @endif

                    @if($paso < 3)
                    <button class="btn text-white font-weight-bold px-4" style="background: #0B5E2E;" wire:click="siguientePaso">
                        Siguiente <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                    @else
                    <button class="btn text-white font-weight-bold px-4" style="background: #C9A227;" wire:click="crearCurso">
                        <i class="fas fa-check mr-1"></i> Crear Curso
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
