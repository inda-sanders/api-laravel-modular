<?php

use App\Http\Middleware\DatabaseMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureApiTokenIsValid;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->group('api', [
        //     EnsureApiTokenIsValid::class,
        // ]);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'checkToken' => EnsureApiTokenIsValid::class,
            'slavering' => DatabaseMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
        //     return response()->json([
        //         'message' => 'You do not have the required authorization.',
        //         'responseCode'  => 403,
        //         'data' => [],
        //     ], 200);
        // });
        // $exceptions->render(function (NotFoundHttpException $e, Request $request) {
        //     if ($request->is('api/*')) {
        //         return response()->json([
        //             'message' => 'Wrong Endpoint',
        //             'responseCode'  => 404,
        //             'data' => [],
        //         ], 200);
        //     }
        // });
        // $exceptions->render(function (\Throwable $e, $request) {
        //     return response()->json([
        //         'message' => $e->getMessage(),
        //         'responseCode'  => $e->getStatusCode(),
        //         'data' => [],
        //     ], 200);
        // });
    })->create();
