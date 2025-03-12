<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $request->lang == 'ar' ?  'المورد غير موجود.' : 'Resource not found.',
                    'data' => $e->getMessage(),
                    'code' => 404,
                ], 404);
            }
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $request->lang == 'ar' ? 'السجل غير موجود.' : 'Record not found.',
                    'data' => $e->getMessage(),
                    'code' => 404,
                ], 404);
            }
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $request->lang == 'ar' ? 'الطريقة غير مسموح بها.' : 'Method not allowed.',
                    'data' => $e->getMessage(),
                    'code' => 405,
                ], 405);
            }
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $request->lang == 'ar' ? 'خطأ في التحقق من البيانات.' : 'Validation error.',
                    'data' => $e->errors(),
                    'code' => 422,
                ], 422);
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $request->lang == 'ar' ? 'غير مصرح لك بالوصول.' : 'Unauthorized access.',
                    'data' => $e->getMessage(),
                    'code' => 401,
                ], 401);
            }
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $request->lang == 'ar' ? 'حدث خطأ غير متوقع.' : 'An unexpected error occurred.',
                    'data' => $e->getMessage(),
                    'code' => 500,
                ], 500);
            }
        });
    })->create();
