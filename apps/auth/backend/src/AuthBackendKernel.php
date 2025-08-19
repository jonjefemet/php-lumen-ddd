<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend;

use Finger\Apps\Auth\Backend\Controller\Auth\LoginPostController;
use Finger\Apps\Auth\Backend\Controller\Auth\RegisterPostController;
use Finger\Apps\Auth\Backend\Controller\HealthCheck\HealthCheckGetController;
use Finger\Apps\Auth\Backend\DependencyInjection\AuthServiceProvider;
use Finger\Shared\Infrastructure\DependencyInjection\Container;
use Finger\Shared\Infrastructure\Http\Router;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthBackendKernel
{
    private string $environment;
    private bool $debug;
    private Container $container;
    private Router $router;

    public function __construct(string $environment = 'dev', bool $debug = true)
    {
        $this->environment = $environment;
        $this->debug = $debug;
        $this->container = $this->buildContainer();
        $this->router = $this->buildRouter();
    }

    private function buildContainer(): Container
    {
        $container = new Container();
        AuthServiceProvider::register($container);
        return $container;
    }

    private function buildRouter(): Router
    {
        $router = new Router($this->container);
        
        // Register all controllers automatically
        $controllers = [
            HealthCheckGetController::class,
            LoginPostController::class,
            RegisterPostController::class,
        ];
        
        $router->registerControllers($controllers);
        
        return $router;
    }

    public function run(): void
    {
        $request = Request::createFromGlobals();
        $response = $this->handle($request);
        $response->send();
    }

    private function handle(Request $request): Response
    {
        try {
            // Try to dispatch the request through the router
            $response = $this->router->dispatch($request);
            
            if ($response !== null) {
                return $response;
            }

            // Route not found
            return new JsonResponse([
                'error' => 'Not found',
                'path' => $request->getPathInfo(),
                'method' => $request->getMethod()
            ], 404);
            
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Internal server error',
                'message' => $this->debug ? $e->getMessage() : 'Something went wrong',
                'trace' => $this->debug ? $e->getTraceAsString() : null
            ], 500);
        }
    }


}
