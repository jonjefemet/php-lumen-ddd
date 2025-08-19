<?php

declare(strict_types=1);

namespace Finger\Tests\Shared\Infrastructure\Mockery;

use Finger\Tests\Shared\Infrastructure\PhpUnit\Constraint\FingerConstraintIsSimilar;
use Mockery\Matcher\MatcherInterface;
use Stringable;

final readonly class FingerMatcherIsSimilar implements Stringable, MatcherInterface
{
    private FingerConstraintIsSimilar $constraint;

    public function __construct(mixed $value, float $delta = 0.0)
    {
        $this->constraint = new FingerConstraintIsSimilar($value, $delta);
    }

    public function match(&$actual): bool
    {
        return $this->constraint->evaluate($actual, '', true);
    }

    public function __toString(): string
    {
        return 'Is similar';
    }
}
