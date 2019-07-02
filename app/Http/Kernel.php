<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
            \App\Laravel\Middleware\Api\ValidResponseFormat::class,
            \Illuminate\Session\Middleware\StartSession::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \App\Http\Middleware\ThrottleRequests::class,

        'backoffice.auth' => \App\Laravel\Middleware\Backoffice\Authenticate::class,
        'backoffice.guest' => \App\Laravel\Middleware\Backoffice\RedirectIfAuthenticated::class,
        'backoffice.verify-reset-token' => \App\Laravel\Middleware\Backoffice\VerifyResetToken::class,
        'backoffice.super_user_only' => \App\Laravel\Middleware\Backoffice\SuperUserOnly::class,
        'backoffice.employee_only' => \App\Laravel\Middleware\Backoffice\EmployeeOnly::class,
        'backoffice.authorized_ip_only' => \App\Laravel\Middleware\Backoffice\AuthorizedIPOnly::class,

        'jwt.auth' => \App\Laravel\Middleware\Api\JWTApiAuth::class,
        'jwt.refresh' => \App\Laravel\Middleware\Api\JWTRefresher::class,

        'api.exists' => \App\Laravel\Middleware\Api\ExistRecord::class,
        'api.verify-reset-token' => \App\Laravel\Middleware\Api\VerifyResetToken::class,
        'api.tokenizer' => \App\Laravel\Middleware\Api\ApiTokenizer::class,
        'api.authorized_ip_only' => \App\Laravel\Middleware\Api\AuthorizedIPOnly::class,

        'frontend.auth' => \App\Laravel\Middleware\Frontend\Authenticate::class,
        'frontend.guest' => \App\Laravel\Middleware\Frontend\RedirectIfAuthenticated::class,
        'frontend.security_question' => \App\Laravel\Middleware\Frontend\CheckSecurityQuestion::class,
        'frontend.verify' => \App\Laravel\Middleware\Frontend\CheckVerification::class,
        'frontend.two_factor' => \App\Laravel\Middleware\Frontend\TwoFactorAuthentication::class,
        'frontend.check_auth' => \App\Laravel\Middleware\Frontend\CheckAuth::class,
        'frontend.check_cart' => \App\Laravel\Middleware\Frontend\CheckCartCount::class,
    ];
}
