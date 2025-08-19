<?php

declare(strict_types=1);

namespace Finger\Tests\Backoffice\Products\Domain;

use Finger\Backoffice\Products\Domain\ProductDescription;
use Finger\Tests\Shared\Domain\WordMother;

final class ProductDescriptionMother
{
    public static function create(?string $value = null): ProductDescription
    {
        return new ProductDescription($value ?? WordMother::sentence(10));
    }
}
