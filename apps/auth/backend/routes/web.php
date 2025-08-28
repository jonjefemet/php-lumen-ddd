<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| API Version 1 Routes
|--------------------------------------------------------------------------
|
| All V1 routes are grouped under /api/v1 prefix for proper versioning.
| Legacy routes without version prefix redirect to v1 for compatibility.
|
*/

// V1 API Routes
$router->group(['prefix' => 'api/v1'], function () use ($router) {
    
    // System routes
    $router->group(['namespace' => 'V1\System'], function () use ($router) {
        $router->get('/health', 'HealthController');
    });
    
    // Auth routes
    $router->group(['prefix' => 'auth', 'namespace' => 'V1\Auth'], function () use ($router) {
        $router->post('/login', 'LoginController');
        $router->post('/register', 'RegisterController');
        $router->post('/refresh', 'RefreshTokenController');
    });
});

/*
|--------------------------------------------------------------------------
| Legacy Routes (Backward Compatibility)
|--------------------------------------------------------------------------
|
| These routes maintain backward compatibility while redirecting to v1.
| Consider deprecating these in future versions.
|
*/

// Legacy system routes (no version) -> redirect to v1
$router->group(['namespace' => 'V1\System'], function () use ($router) {
    $router->get('/health', 'HealthController');
});

// Legacy auth routes -> redirect to v1
$router->group(['prefix' => 'api/auth', 'namespace' => 'V1\Auth'], function () use ($router) {
    $router->post('/login', 'LoginController');
    $router->post('/register', 'RegisterController');
    $router->post('/refresh', 'RefreshTokenController');
});
