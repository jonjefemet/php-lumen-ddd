<?php

declare(strict_types=1);

namespace Finger\Tests\Shared\Domain;

final class IntegerMother
{
    public static function create(): int
    {
        return self::between(1);
    }

    public static function between(int $min, int $max = PHP_INT_MAX): int
    {
        return MotherCreator::random()->numberBetween($min, $max);
    }

    public static function lessThan(int $max): int
    {
        return self::between(1, $max);
    }

    public static function positive(): int
    {
        return self::between(1, 1000);
    }
}
