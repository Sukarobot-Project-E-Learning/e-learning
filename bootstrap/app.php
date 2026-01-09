<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'instructor' => \App\Http\Middleware\EnsureUserIsInstructor::class,
            'user' => \App\Http\Middleware\EnsureUserIsUser::class,
            'jwt.auth' => \App\Http\Middleware\JWTAuthenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Helper function to handle 419 redirect
        $handle419 = function ($request) {
            $url = $request->url();
            
            // If on admin login, redirect back to admin login with message
            if (str_contains($url, '/admin/login')) {
                return redirect()->route('admin.login')
                    ->with('warning', 'Halaman expired, silakan coba lagi.');
            }
            
            // If on instructor login, redirect back to instructor login
            if (str_contains($url, '/instructor/login')) {
                return redirect()->route('instructor.login')
                    ->with('warning', 'Halaman expired, silakan coba lagi.');
            }
            
            // If on user login, redirect back to user login
            if (str_contains($url, '/login')) {
                return redirect()->route('login')
                    ->with('warning', 'Halaman expired, silakan coba lagi.');
            }
            
            // For other pages, redirect back with error
            return redirect()->back()
                ->with('error', 'Sesi telah berakhir, silakan refresh halaman.');
        };
        
        // Handle TokenMismatchException (CSRF token mismatch)
        $exceptions->render(function (TokenMismatchException $e, $request) use ($handle419) {
            return $handle419($request);
        });
        
        // Handle HttpException with 419 status code
        $exceptions->render(function (HttpException $e, $request) use ($handle419) {
            if ($e->getStatusCode() === 419) {
                return $handle419($request);
            }
            return null; // Let other HTTP exceptions be handled normally
        });
    })->create();
