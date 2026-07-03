<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()->route('login')
                ->with('error', 'Tu sesion ha expirado por inactividad. Inicia sesion nuevamente.');
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return redirect()->route('login');
        }

        return parent::render($request, $exception);
    }
}
