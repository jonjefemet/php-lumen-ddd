<?php

declare(strict_types=1);

namespace Finger\Tests\Backoffice\Products\Domain;

use Finger\Backoffice\Products\Domain\ProductPrice;
use Finger\Tests\Shared\Domain\IntegerMother;
use Finger\Tests\Shared\Domain\RandomElementPicker;

final class ProductPriceMother
{
    public static function create(?float $amount = null, ?string $currency = null): ProductPrice
    {
        return new ProductPrice(
            $amount ?? self::randomAmount(),
            $currency ?? self::randomCurrency()
        );
    }

    public static function expensive(): ProductPrice
    {
        return self::create(IntegerMother::between(500, 2000));
    }

    public static function cheap(): ProductPrice
    {
        return self::create(IntegerMother::between(1, 50));
    }

    public static function withCurrency(string $currency): ProductPrice
    {
        return self::create(currency: $currency);
    }

    private static function randomAmount(): float
    {
        return IntegerMother::between(10, 1000) + (IntegerMother::between(0, 99) / 100);
    }

    private static function randomCurrency(): string
    {
        return RandomElementPicker::from('USD', 'EUR');
    }
}
