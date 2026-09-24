<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\TrackReferral;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'admin' => IsAdmin::class,
        ]);
        $middleware->web(append: [
            TrackReferral::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Thông báo tiếng Việt khi bị chặn đăng nhập (throttle:login)
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if ($request->is('login') && $request->isMethod('POST')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bạn đã nhập sai nhiều lần. Vui lòng thử lại sau ít phút.',
                    ], 429);
                }

                return redirect()
                    ->route('login')
                    ->withErrors(['email' => 'Bạn đã nhập sai nhiều lần. Vui lòng thử lại sau ít phút.'])
                    ->withInput($request->only('email'));
            }
        });
    })->create();
