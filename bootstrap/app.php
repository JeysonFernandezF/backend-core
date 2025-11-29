<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // <-- AÑADE ESTA

// --- AÑADE TODAS LAS CLASES DE EXCEPCIÓN AQUÍ ---
use App\Exceptions\ApiException; 
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
// use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $isApi = function (Request $request) {
            return $request->expectsJson() || $request->wantsJson() || $request->is('api/*');
        };

        // Manejo de ApiException (y todas sus subclases)
        $exceptions->renderable(function (ApiException $e, Request $request) use ($isApi) {
            if ($isApi($request)) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => $e->codeStr,
                    'message' => $e->getMessage(),
                    'details' => config('app.debug') ? $e->details : null, 
                ], $e->httpStatus);
            }
        });

        // ValidationException
        $exceptions->renderable(function (ValidationException $e, Request $request) use ($isApi) {
            if ($isApi($request)) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => 'validation_error',
                    'message' => 'Los datos enviados no son válidos.',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });

        // AuthenticationException
        $exceptions->renderable(function (AuthenticationException $e, Request $request) use ($isApi) {
            if ($isApi($request)) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => 'unauthenticated',
                    'message' => 'No autenticado.',
                ], 401);
            }
        });

        // Fallback para cualquier OTRO error
        $exceptions->renderable(function (Throwable $e, Request $request) use ($isApi) {
            if ($isApi($request)) {
                
                $httpStatus = method_exists($e, 'getStatusCode') 
                                ? $e->getStatusCode() 
                                : (property_exists($e, 'httpStatus') ? $e->httpStatus : 500);

                $message = config('app.debug') 
                                ? $e->getMessage() 
                                : 'Error interno del servidor.';

                $details = config('app.debug') 
                                ? [
                                    'exception' => get_class($e),
                                    'file' => $e->getFile(),
                                    'line' => $e->getLine()
                                ]
                                : null;

                return response()->json([
                    'status'  => 'error',
                    'code'    => 'server_error',
                    'message' => $message,
                    'details' => $details
                ], $httpStatus);
            }
        });
    })->create();
