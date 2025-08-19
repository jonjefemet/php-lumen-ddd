<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Domain;

use Finger\Shared\Domain\ValueObject\StringValueObject;
use InvalidArgumentException;

final class UserName extends StringValueObject
{
    public function __construct(string $value)
    {
        $this->ensureIsNotEmpty($value);
        $this->ensureDoesNotExceedMaxLength($value);
        
        parent::__construct(trim($value));
    }

    private function ensureIsNotEmpty(string $value): void
    {
        if (empty(trim($value))) {
            throw new InvalidArgumentException('User name cannot be empty');
        }
    }

    private function ensureDoesNotExceedMaxLength(string $value): void
    {
        if (strlen($value) > 255) {
            throw new InvalidArgumentException('User name cannot exceed 255 characters');
        }
    }
}
