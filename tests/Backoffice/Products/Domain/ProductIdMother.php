<?php

declare(strict_types=1);

namespace Finger\Tests\Backoffice\Products\Domain;

use Finger\Backoffice\Products\Domain\ProductId;
use Finger\Tests\Shared\Domain\UuidMother;

final class ProductIdMother
{
    public static function create(?string $value = null): ProductId
    {
        return new ProductId($value ?? UuidMother::create());
    }
}
