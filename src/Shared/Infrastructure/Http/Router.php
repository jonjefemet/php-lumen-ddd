<?php

declare(strict_types=1);

namespace Finger\Shared\Infrastructure\Http;

use Finger\Shared\Infrastructure\DependencyInjection\Container;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Router
{
    private array $routes = [];

    public function __construct(
        private Container $container
    ) {
    }

    public function registerController(string $controllerClass): void
    {
        $reflection = new ReflectionClass($controllerClass);
        $attributes = $reflection->getAttributes(Route::class);

        if (empty($attributes)) {
            return;
        }

        foreach ($attributes as $attribute) {
            /** @var Route $route */
            $route = $attribute->newInstance();
            
            $key = $route->method . ':' . $route->path;
            $this->routes[$key] = $controllerClass;
        }
    }

    public function registerControllers(array $controllerClasses): void
    {
        foreach ($controllerClasses as $controllerClass) {
            $this->registerController($controllerClass);
        }
    }

    public function dispatch(Request $request): ?Response
    {
        $method = $request->getMethod();
        $path = $request->getPathInfo();
        $key = $method . ':' . $path;

        if (!isset($this->routes[$key])) {
            return null;
        }

        $controllerClass = $this->routes[$key];
        $controller = $this->resolveController($controllerClass);

        return $controller($request);
    }

    private function resolveController(string $controllerClass): object
    {
        // Try to get from container first (for controllers with dependencies)
        try {
            return $this->container->get($controllerClass);
        } catch (\Exception) {
            // Fallback to direct instantiation if not registered in container
            return new $controllerClass();
        }
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
