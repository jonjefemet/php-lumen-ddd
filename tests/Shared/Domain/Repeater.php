<?php

declare(strict_types=1);

namespace Finger\Tests\Shared\Domain;

final class Repeater
{
    public static function repeat(callable $function, int $quantity): array
    {
        return array_map(fn() => $function(), range(1, $quantity));
    }

    public static function random(callable $function): array
    {
        return self::repeat($function, IntegerMother::lessThan(5));
    }
}
