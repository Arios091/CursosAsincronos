<?php

use App\Http\Controllers\Auth\CompleteProfileController;
use App\Http\Controllers\Auth\PendingVerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PageSettingsController;
use App\Http\Controllers\UserController;
use App\Http\Livewire\CreateCurso;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Verificacion de email (rutas requeridas por el middleware 'verified')
Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/verify/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// Rutas publicas
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/verificar/{codigo}', [CursoController::class, 'verificarCertificadoPublico'])
    ->name('verificar.certificado');

Route::get('/api/verificar/{codigo}', [CursoController::class, 'verificarCertificado'])
    ->name('api.verificar.certificado');

// Autenticacion
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/registro/verificar', [PendingVerificationController::class, 'showPending'])
    ->name('pending.verification');
Route::get('/registro/verificar/{token}', [PendingVerificationController::class, 'verify'])
    ->name('pending.verify');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Rutas autenticadas
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/completar-perfil', [CompleteProfileController::class, 'showForm'])->name('completar.perfil');
    Route::post('/completar-perfil', [CompleteProfileController::class, 'save'])->name('completar.perfil.guardar');

    // Cursos
    Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');
    Route::get('/cursos/{curso}', [CursoController::class, 'show'])->name('cursos.show');

    Route::post('/cursos/{curso}/comenzar', [CursoController::class, 'comenzar'])->name('cursos.comenzar');
    Route::get('/mis-cursos/{curso}', [CursoController::class, 'verCurso'])->name('mis-cursos');

    Route::post('/cursos/material/{material}/completar', [CursoController::class, 'completarMaterial'])
        ->name('cursos.completar-material');
    Route::post('/cursos/material/{material}/cuestionario', [CursoController::class, 'enviarCuestionario'])
        ->name('cursos.cuestionario');
    Route::post('/cursos/modulo/{modulo}/cuestionario', [CursoController::class, 'enviarCuestionarioModulo'])
        ->name('cursos.cuestionario.modulo');

    Route::post('/cursos/{curso}/examen-final', [CursoController::class, 'enviarExamenFinal'])
        ->name('cursos.examen-final');

    Route::get('/cursos/{curso}/completado', [CursoController::class, 'completado'])->name('cursos.completado');

    // Materiales
    Route::get('/material/{material}', [MaterialController::class, 'show'])->name('material.show');
    Route::put('/material/{material}', [MaterialController::class, 'update'])->name('material.update');
    Route::delete('/material/{material}', [MaterialController::class, 'destroy'])->name('material.destroy');

    // Archivos
    Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
    Route::post('/files/delete', [FileController::class, 'delete'])->name('files.delete');

    // Servir archivos de storage sin symlink
    Route::get('/storage/{path}', [FileController::class, 'serve'])
        ->where('path', '.*')
        ->name('storage.serve');

    // Certificados
    Route::get('/certificado/{curso}', [CertificadoController::class, 'ver'])->name('certificado.ver');
    Route::get('/certificado/{curso}/descargar', [CertificadoController::class, 'descargar'])->name('certificado.descargar');

    // CRUD de cursos (solo admin y admin_global)
    Route::middleware(['role:admin,admin_global'])->group(function () {
        Route::get('/crear.curso', CreateCurso::class)->name('crear.curso');
        Route::get('/cursos/{curso}/editar', \App\Http\Livewire\EditCurso::class)->name('cursos.editar');
        Route::delete('/cursos/{curso}', [CursoController::class, 'destroy'])->name('cursos.destroy');
    });

    // Panel admin_global
    Route::middleware(['role:admin_global'])->group(function () {
        Route::get('/admin/usuarios', [UserController::class, 'index'])->name('admin.usuarios.index');
        Route::get('/admin/usuarios/{user}', [UserController::class, 'show'])->name('admin.usuarios.show');
        Route::put('/admin/usuarios/{user}', [UserController::class, 'update'])->name('admin.usuarios.update');
        Route::delete('/admin/usuarios/{user}', [UserController::class, 'destroy'])->name('admin.usuarios.destroy');

        Route::get('/admin/page-settings', [PageSettingsController::class, 'index'])->name('admin.page-settings');
        Route::post('/admin/page-settings', [PageSettingsController::class, 'update'])->name('admin.page-settings.update');
    });
});
