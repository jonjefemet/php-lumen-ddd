<?php

declare(strict_types=1);

namespace Finger\Shared\Infrastructure\Bus;

use ReflectionClass;
use ReflectionException;
use function Lambdish\Phunctional\reduce;
use function Lambdish\Phunctional\reindex;

final class CallableFirstParameterExtractor
{
    public static function forCallables(iterable $callables): array
    {
        return reduce(self::unfoldCallable(), $callables, []);
    }

    public static function forPipedCallables(iterable $callables): array
    {
        return reindex(self::pipedCallableExtractor(), $callables);
    }

    private static function unfoldCallable(): callable
    {
        return static function (array $accumulator, callable $handler): array {
            $parameters = self::firstParameterOf($handler);

            foreach ($parameters as $parameter) {
                $accumulator[$parameter] = $handler;
            }

            return $accumulator;
        };
    }

    private static function pipedCallableExtractor(): callable
    {
        return static function (callable $handler): string {
            return self::firstParameterOf($handler)[0];
        };
    }

    private static function firstParameterOf(callable $class): array
    {
        try {
            $reflection = new ReflectionClass($class);
            $method = $reflection->getMethod('__invoke');

            if ($method->getNumberOfParameters() === 1) {
                return [$method->getParameters()[0]->getType()?->getName()];
            }

            return [];
        } catch (ReflectionException) {
            return [];
        }
    }
}
