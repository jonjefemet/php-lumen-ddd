<?php

declare(strict_types=1);

namespace Finger\Shared\Infrastructure\DependencyInjection;

use Closure;

final class Container
{
    private array $services = [];
    private array $instances = [];

    public function bind(string $abstract, Closure|string $concrete): void
    {
        $this->services[$abstract] = $concrete;
    }

    public function singleton(string $abstract, Closure|string $concrete): void
    {
        $this->bind($abstract, $concrete);
        $this->instances[$abstract] = null;
    }

    public function get(string $abstract): object
    {
        // If it's a singleton and already instantiated, return it
        if (array_key_exists($abstract, $this->instances) && $this->instances[$abstract] !== null) {
            return $this->instances[$abstract];
        }

        // If service is not registered, try to auto-resolve
        if (!isset($this->services[$abstract])) {
            $instance = $this->resolve($abstract);
        } else {
            $concrete = $this->services[$abstract];

            if ($concrete instanceof Closure) {
                $instance = $concrete($this);
            } else {
                $instance = $this->resolve($concrete);
            }
        }

        // Store singleton instance
        if (array_key_exists($abstract, $this->instances)) {
            $this->instances[$abstract] = $instance;
        }

        return $instance;
    }

    private function resolve(string $class): object
    {
        $reflectionClass = new \ReflectionClass($class);

        if (!$reflectionClass->isInstantiable()) {
            throw new \Exception("Class {$class} is not instantiable");
        }

        $constructor = $reflectionClass->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type === null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Cannot resolve dependency {$parameter->getName()}");
                }
            } elseif ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                $dependencies[] = $this->get($type->getName());
            } else {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Cannot resolve dependency {$parameter->getName()}");
                }
            }
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}
