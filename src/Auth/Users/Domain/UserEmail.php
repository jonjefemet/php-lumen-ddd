<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Domain;

use Finger\Shared\Domain\ValueObject\StringValueObject;
use InvalidArgumentException;

final class UserEmail extends StringValueObject
{
    public function __construct(string $value)
    {
        $this->ensureIsValidEmail($value);
        parent::__construct(strtolower(trim($value)));
    }

    private function ensureIsValidEmail(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }
    }
}
