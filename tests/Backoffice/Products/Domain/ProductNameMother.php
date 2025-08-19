<?php

declare(strict_types=1);

namespace Finger\Tests\Backoffice\Products\Domain;

use Finger\Backoffice\Products\Domain\ProductName;
use Finger\Tests\Shared\Domain\WordMother;

final class ProductNameMother
{
    public static function create(?string $value = null): ProductName
    {
        return new ProductName($value ?? WordMother::words(3, true));
    }
}
