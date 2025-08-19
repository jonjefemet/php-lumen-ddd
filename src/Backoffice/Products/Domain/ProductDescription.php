<?php

declare(strict_types=1);

namespace Finger\Backoffice\Products\Domain;

use Finger\Shared\Domain\ValueObject\StringValueObject;

final class ProductDescription extends StringValueObject
{
    public function __construct(string $value)
    {
        $this->ensureIsNotTooLong($value);
        parent::__construct($value);
    }

    private function ensureIsNotTooLong(string $value): void
    {
        if (strlen($value) > 1000) {
            throw new \InvalidArgumentException('Product description cannot exceed 1000 characters');
        }
    }
}
