@extends('layouts.app')

@php
$activeTab = old('_tab', 'general');
@endphp

@section('content')
<div class="container">
    <h4 class="font-weight-bold mb-1" style="color: #0B5E2E;">
        <i class="fas fa-palette mr-2"></i> Configuración Visual
    </h4>
    <p class="text-muted mb-4">Personaliza la apariencia y el contenido del sitio público.</p>

    <form method="POST" action="{{ route('admin.page-settings.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_tab" id="active_tab" value="{{ $activeTab }}">

        <ul class="nav nav-tabs nav-fill mb-4" id="settingsTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'general' ? 'active' : '' }}" id="tab-general" data-toggle="tab" href="#general" role="tab">
                    <i class="fas fa-cog mr-1"></i> General
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'colores' ? 'active' : '' }}" id="tab-colores" data-toggle="tab" href="#colores" role="tab">
                    <i class="fas fa-paint-brush mr-1"></i> Colores
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'hero' ? 'active' : '' }}" id="tab-hero" data-toggle="tab" href="#hero" role="tab">
                    <i class="fas fa-image mr-1"></i> Hero
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'imagenes' ? 'active' : '' }}" id="tab-imagenes" data-toggle="tab" href="#imagenes" role="tab">
                    <i class="fas fa-file-image mr-1"></i> Imágenes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'redes' ? 'active' : '' }}" id="tab-redes" data-toggle="tab" href="#redes" role="tab">
                    <i class="fas fa-share-alt mr-1"></i> Redes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'contacto' ? 'active' : '' }}" id="tab-contacto" data-toggle="tab" href="#contacto" role="tab">
                    <i class="fas fa-phone mr-1"></i> Contacto
                </a>
            </li>
        </ul>

        <div class="tab-content">
            {{-- TAB: GENERAL --}}
            <div class="tab-pane fade {{ $activeTab == 'general' ? 'show active' : '' }}" id="general" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header font-weight-bold"><i class="fas fa-university mr-1"></i> Información de la Institución</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre de la Institución</label>
                                    <input type="text" name="institution_name" class="form-control"
                                        value="{{ $settings['institution_name']->value ?? 'Universidad Nacional Agraria de la Selva' }}">
                                    <small class="form-text text-muted">Se muestra en el pie de página y certificados.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Acrónimo / Siglas</label>
                                    <input type="text" name="institution_acronym" class="form-control"
                                        value="{{ $settings['institution_acronym']->value ?? 'UNAS' }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Meta Descripción (SEO)</label>
                            <textarea name="meta_description" class="form-control" rows="2">{{ $settings['meta_description']->value ?? 'Plataforma oficial de educación continua de la Universidad Nacional Agraria de la Selva' }}</textarea>
                            <small class="form-text text-muted">Se usa para la descripción en buscadores y compartir en redes.</small>
                        </div>
                        <div class="form-group">
                            <label>Texto del Pie de Página</label>
                            <textarea name="footer_text" class="form-control" rows="2">{{ $settings['footer_text']->value ?? '© 2024 Universidad Nacional Agraria de la Selva. Todos los derechos reservados.' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: COLORES --}}
            <div class="tab-pane fade {{ $activeTab == 'colores' ? 'show active' : '' }}" id="colores" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header font-weight-bold"><i class="fas fa-paint-brush mr-1"></i> Colores del Sistema</div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Estos colores se aplican en el sitio público y en los certificados.</p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Color Primario</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text color-preview" style="background: {{ $settings['primary_color']->value ?? '#0B5E2E' }};"></span>
                                        </div>
                                        <input type="color" name="primary_color" class="form-control form-control-color"
                                            value="{{ $settings['primary_color']->value ?? '#0B5E2E' }}">
                                    </div>
                                    <small class="form-text text-muted">Verde institucional — encabezados, botones principales.</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Color Secundario</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text color-preview" style="background: {{ $settings['secondary_color']->value ?? '#C9A227' }};"></span>
                                        </div>
                                        <input type="color" name="secondary_color" class="form-control form-control-color"
                                            value="{{ $settings['secondary_color']->value ?? '#C9A227' }}">
                                    </div>
                                    <small class="form-text text-muted">Dorado — acentos, barras de progreso.</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Barra de Progreso</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text color-preview" style="background: {{ $settings['progress_bar_color']->value ?? '#C9A227' }};"></span>
                                        </div>
                                        <input type="color" name="progress_bar_color" class="form-control form-control-color"
                                            value="{{ $settings['progress_bar_color']->value ?? '#C9A227' }}">
                                    </div>
                                    <small class="form-text text-muted">Color de las barras de progreso en cursos.</small>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <label class="font-weight-bold">Vista previa de colores</label>
                                <div class="d-flex align-items-center flex-wrap">
                                    <span class="color-badge mr-3 mb-2" style="background: {{ $settings['primary_color']->value ?? '#0B5E2E' }}; color: #fff; padding: 6px 18px; border-radius: 4px;">Primario</span>
                                    <span class="color-badge mr-3 mb-2" style="background: {{ $settings['secondary_color']->value ?? '#C9A227' }}; color: #fff; padding: 6px 18px; border-radius: 4px;">Secundario</span>
                                    <span class="color-badge mb-2" style="background: {{ $settings['progress_bar_color']->value ?? '#C9A227' }}; color: #fff; padding: 6px 18px; border-radius: 4px;">Progreso</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: HERO --}}
            <div class="tab-pane fade {{ $activeTab == 'hero' ? 'show active' : '' }}" id="hero" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header font-weight-bold"><i class="fas fa-image mr-1"></i> Sección Hero (Portada)</div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Contenido de la sección principal en la página de inicio.</p>
                        <div class="form-group">
                            <label>Título <small class="text-muted">(HTML permitido)</small></label>
                            <input type="text" name="hero_title" class="form-control"
                                value="{{ $settings['hero_title']->value ?? 'Sistema de <span>Gestion de Docencia</span> UNAS' }}">
                        </div>
                        <div class="form-group">
                            <label>Subtítulo</label>
                            <textarea name="hero_subtitle" class="form-control" rows="3">{{ $settings['hero_subtitle']->value ?? 'Plataforma oficial de educacion continua de la Universidad Nacional Agraria de la Selva' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: IMÁGENES --}}
            <div class="tab-pane fade {{ $activeTab == 'imagenes' ? 'show active' : '' }}" id="imagenes" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header font-weight-bold"><i class="fas fa-file-image mr-1"></i> Imágenes del Sitio</div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Sube imágenes para personalizar el sitio. Las imágenes existentes se muestran abajo; puedes reemplazarlas o eliminarlas.</p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <label class="font-weight-bold">Logo</label>
                                        <div class="custom-file mb-2">
                                            <input type="file" name="logo" class="custom-file-input" id="logoInput" accept="image/*">
                                            <label class="custom-file-label" for="logoInput">Seleccionar archivo</label>
                                        </div>
                                        <small class="form-text text-muted mb-1"><i class="fas fa-info-circle mr-1"></i>Recomendado: <b>400 × 150 px</b> (PNG con fondo transparente). Se muestra a 40px de alto en el encabezado.</small>
                                        @if(isset($settings['logo']) && $settings['logo']->value)
                                        <div class="d-flex align-items-center justify-content-between">
                                            <img src="{{ storage_url($settings['logo']->value) }}" style="max-height: 50px;" class="mr-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="delete_logo" class="custom-control-input" id="deleteLogo">
                                                <label class="custom-control-label text-danger" for="deleteLogo">Eliminar</label>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <label class="font-weight-bold">Favicon</label>
                                        <div class="custom-file mb-2">
                                            <input type="file" name="favicon" class="custom-file-input" id="faviconInput" accept="image/x-icon,image/png">
                                            <label class="custom-file-label" for="faviconInput">Seleccionar archivo</label>
                                        </div>
                                        <small class="form-text text-muted mb-1"><i class="fas fa-info-circle mr-1"></i>Recomendado: <b>32 × 32 px</b> (PNG o ICO). Icono de la pestana del navegador.</small>
                                        @if(isset($settings['favicon']) && $settings['favicon']->value)
                                        <div class="d-flex align-items-center justify-content-between">
                                            <img src="{{ storage_url($settings['favicon']->value) }}" style="max-height: 32px;" class="mr-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="delete_favicon" class="custom-control-input" id="deleteFavicon">
                                                <label class="custom-control-label text-danger" for="deleteFavicon">Eliminar</label>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <label class="font-weight-bold">Fondo de Login</label>
                                        <div class="custom-file mb-2">
                                            <input type="file" name="login_bg" class="custom-file-input" id="loginBgInput" accept="image/*">
                                            <label class="custom-file-label" for="loginBgInput">Seleccionar archivo</label>
                                        </div>
                                        <small class="form-text text-muted mb-1"><i class="fas fa-info-circle mr-1"></i>Recomendado: <b>1920 × 1080 px</b> (fondo a pantalla completa).</small>
                                        @if(isset($settings['login_bg']) && $settings['login_bg']->value)
                                        <div class="d-flex align-items-center justify-content-between">
                                            <img src="{{ storage_url($settings['login_bg']->value) }}" style="max-height: 50px;" class="mr-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="delete_login_bg" class="custom-control-input" id="deleteLoginBg">
                                                <label class="custom-control-label text-danger" for="deleteLoginBg">Eliminar</label>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <label class="font-weight-bold">Imágenes del Carrusel</label>
                        <div class="row">
                            @for($i = 1; $i <= 4; $i++)
                            @php $carouselKey = 'carousel_' . $i; @endphp
                            <div class="col-md-3">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <label class="font-weight-bold">Carousel {{ $i }}</label>
                                        <div class="custom-file mb-2">
                                            <input type="file" name="carousel_{{ $i }}" class="custom-file-input" id="carousel{{ $i }}Input" accept="image/*">
                                            <label class="custom-file-label" for="carousel{{ $i }}Input">Seleccionar</label>
                                        </div>
                                        <small class="form-text text-muted mb-1"><i class="fas fa-info-circle mr-1"></i>Recomendado: <b>1920 × 600 px</b> (banner de la portada).</small>
                                        @if(isset($settings[$carouselKey]) && $settings[$carouselKey]->value)
                                        <div>
                                            <img src="{{ storage_url($settings[$carouselKey]->value) }}" style="max-height: 60px; width: 100%; object-fit: cover;" class="mb-1">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="delete_carousel_{{ $i }}" class="custom-control-input" id="deleteCarousel{{ $i }}">
                                                <label class="custom-control-label text-danger small" for="deleteCarousel{{ $i }}">Eliminar</label>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: REDES SOCIALES --}}
            <div class="tab-pane fade {{ $activeTab == 'redes' ? 'show active' : '' }}" id="redes" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header font-weight-bold"><i class="fas fa-share-alt mr-1"></i> Redes Sociales</div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Enlaces a las redes sociales de la institución. Se muestran como iconos en el pie de página.</p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fab fa-facebook text-primary mr-1"></i> Facebook</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                                        </div>
                                        <input type="url" name="social_facebook" class="form-control"
                                            value="{{ $settings['social_facebook']->value ?? '' }}"
                                            placeholder="https://facebook.com/...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fab fa-twitter text-info mr-1"></i> Twitter / X</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                        </div>
                                        <input type="url" name="social_twitter" class="form-control"
                                            value="{{ $settings['social_twitter']->value ?? '' }}"
                                            placeholder="https://twitter.com/...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fab fa-instagram text-danger mr-1"></i> Instagram</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                        </div>
                                        <input type="url" name="social_instagram" class="form-control"
                                            value="{{ $settings['social_instagram']->value ?? '' }}"
                                            placeholder="https://instagram.com/...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: CONTACTO --}}
            <div class="tab-pane fade {{ $activeTab == 'contacto' ? 'show active' : '' }}" id="contacto" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header font-weight-bold"><i class="fas fa-phone mr-1"></i> Información de Contacto</div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Datos de contacto que se muestran en el pie de página y en la página de inicio.</p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fas fa-phone-alt mr-1"></i> Teléfono</label>
                                    <input type="text" name="contact_phone" class="form-control"
                                        value="{{ $settings['contact_phone']->value ?? '(062) 562341' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fas fa-envelope mr-1"></i> Email</label>
                                    <input type="email" name="contact_email" class="form-control"
                                        value="{{ $settings['contact_email']->value ?? 'mesadepartes@unas.edu.pe' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fas fa-map-marker-alt mr-1"></i> Dirección</label>
                                    <input type="text" name="contact_address" class="form-control"
                                        value="{{ $settings['contact_address']->value ?? 'Carretera Central Km. 1.21, Tingo Maria' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 mb-5">
            <button type="button" class="btn btn-outline-secondary" onclick="restoreDefaults()">
                <i class="fas fa-undo mr-1"></i> Restablecer valores por defecto
            </button>
            <button type="submit" class="btn text-white font-weight-bold px-5 py-2" style="background: #0B5E2E;">
                <i class="fas fa-save mr-1"></i> Guardar Configuración
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.custom-file-input').forEach(input => {
    input.addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'Seleccionar archivo';
        var label = this.nextElementSibling;
        if (label) label.textContent = fileName;
    });
});

document.querySelectorAll('input[type="color"]').forEach(input => {
    input.addEventListener('input', function() {
        var preview = this.closest('.input-group').querySelector('.color-preview');
        if (preview) preview.style.background = this.value;
        updateColorPreviews();
    });
});

function updateColorPreviews() {
    var primary = document.querySelector('input[name="primary_color"]');
    var secondary = document.querySelector('input[name="secondary_color"]');
    var progress = document.querySelector('input[name="progress_bar_color"]');
    if (primary) {
        document.querySelectorAll('.color-badge').forEach(el => {
            if (el.textContent.trim() === 'Primario') el.style.background = primary.value;
        });
    }
    if (secondary) {
        document.querySelectorAll('.color-badge').forEach(el => {
            if (el.textContent.trim() === 'Secundario') el.style.background = secondary.value;
        });
    }
    if (progress) {
        document.querySelectorAll('.color-badge').forEach(el => {
            if (el.textContent.trim() === 'Progreso') el.style.background = progress.value;
        });
    }
}

document.querySelectorAll('.nav-link[data-toggle="tab"]').forEach(function(tab) {
    tab.addEventListener('shown.bs.tab', function(e) {
        document.getElementById('active_tab').value = e.target.getAttribute('href').substring(1);
    });
});

function restoreDefaults() {
    showConfirm('¿Restablecer todos los valores a sus valores por defecto? Esta acción no se puede deshacer.', function() {
        var defaults = {
            'primary_color': '#0B5E2E',
            'secondary_color': '#C9A227',
            'progress_bar_color': '#C9A227',
            'hero_title': 'Sistema de <span>Gestion de Docencia</span> UNAS',
            'hero_subtitle': 'Plataforma oficial de educacion continua de la Universidad Nacional Agraria de la Selva',
            'contact_phone': '(062) 562341',
            'contact_email': 'mesadepartes@unas.edu.pe',
            'contact_address': 'Carretera Central Km. 1.21, Tingo Maria',
            'institution_name': 'Universidad Nacional Agraria de la Selva',
            'institution_acronym': 'UNAS',
            'meta_description': '',
            'footer_text': '',
            'social_facebook': '',
            'social_twitter': '',
            'social_instagram': '',
        };
        Object.keys(defaults).forEach(function(key) {
            var input = document.querySelector('[name="' + key + '"]');
            if (input) {
                input.value = defaults[key];
                if (input.type === 'color') {
                    input.dispatchEvent(new Event('input'));
                }
            }
        });
    });
}
</script>
@endpush

@push('styles')
<style>
.color-preview {
    width: 40px;
    border: 1px solid #ced4da;
}
.nav-tabs .nav-link {
    color: #495057;
    font-size: 0.9rem;
}
.nav-tabs .nav-link.active {
    color: #0B5E2E;
    font-weight: 600;
    border-bottom-color: #0B5E2E;
}
.card {
    border-radius: 8px;
}
.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}
</style>
@endpush
