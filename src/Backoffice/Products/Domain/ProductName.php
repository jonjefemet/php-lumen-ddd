<?php

declare(strict_types=1);

namespace Finger\Backoffice\Products\Domain;

use Finger\Shared\Domain\ValueObject\StringValueObject;

final class ProductName extends StringValueObject
{
    public function __construct(string $value)
    {
        $this->ensureIsNotEmpty($value);
        $this->ensureIsNotTooLong($value);
        parent::__construct($value);
    }

    private function ensureIsNotEmpty(string $value): void
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('Product name cannot be empty');
        }
    }

    private function ensureIsNotTooLong(string $value): void
    {
        if (strlen($value) > 255) {
            throw new \InvalidArgumentException('Product name cannot exceed 255 characters');
        }
    }
}
