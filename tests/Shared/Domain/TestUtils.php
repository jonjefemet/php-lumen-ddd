<?php

declare(strict_types=1);

namespace Finger\Tests\Shared\Domain;

use Finger\Tests\Shared\Infrastructure\Mockery\FingerMatcherIsSimilar;
use Finger\Tests\Shared\Infrastructure\PhpUnit\Constraint\FingerConstraintIsSimilar;

final class TestUtils
{
    public static function isSimilar(mixed $expected, mixed $actual): bool
    {
        $constraint = new FingerConstraintIsSimilar($expected);

        return $constraint->evaluate($actual, '', true);
    }

    public static function assertSimilar(mixed $expected, mixed $actual): void
    {
        $constraint = new FingerConstraintIsSimilar($expected);

        $constraint->evaluate($actual);
    }

    public static function similarTo(mixed $value, float $delta = 0.0): FingerMatcherIsSimilar
    {
        return new FingerMatcherIsSimilar($value, $delta);
    }
}
