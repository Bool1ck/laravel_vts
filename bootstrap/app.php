<?php

declare(strict_types=1);

use App\Http\Middleware\IsAdminInRegionMiddleware;
use App\Http\Middleware\IsCanEditRegionMiddleware;
use App\Http\Middleware\IsCanViewRegionMiddleware;
use App\Http\Middleware\NoCacheMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'UserIsAdminInRegion' => IsAdminInRegionMiddleware::class,
            'UserCanEditRegion' => IsCanEditRegionMiddleware::class,
            'UserCanViewRegion' => IsCanViewRegionMiddleware::class,
            'NoCache' => NoCacheMiddleware::class,
        ]);
        $middleware->statefulApi();
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //        // Хитрый трюк для отладки: принудительно ловим ЛЮБУЮ ошибку 404
        //        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
        //
        //            // Если у ошибки есть предыдущее исключение (например, ModelNotFoundException из шаблона)
        //            if ($e->getPrevious()) {
        //                dd([
        //                    '🚨 РЕАЛЬНАЯ ПРИЧИНА КРАША' => $e->getPrevious()->getMessage(),
        //                    'Файл' => $e->getPrevious()->getFile(),
        //                    'Строка' => $e->getPrevious()->getLine(),
        //                ]);
        //            }
        //
        //            // Если это чистый abort(404)
        //            dd([
        //                'Тип ошибки' => 'Чистый вызов abort(404) или отсутствие роута',
        //                'Файл' => $e->getFile(),
        //                'Строка' => $e->getLine(),
        //            ]);
        //        });
    })->create();
