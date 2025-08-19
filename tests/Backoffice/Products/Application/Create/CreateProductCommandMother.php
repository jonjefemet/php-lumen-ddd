<?php

declare(strict_types=1);

namespace Finger\Tests\Backoffice\Products\Application\Create;

use Finger\Backoffice\Products\Application\Create\CreateProductCommand;
use Finger\Backoffice\Products\Domain\ProductDescription;
use Finger\Backoffice\Products\Domain\ProductId;
use Finger\Backoffice\Products\Domain\ProductName;
use Finger\Backoffice\Products\Domain\ProductPrice;
use Finger\Tests\Backoffice\Products\Domain\ProductDescriptionMother;
use Finger\Tests\Backoffice\Products\Domain\ProductIdMother;
use Finger\Tests\Backoffice\Products\Domain\ProductNameMother;
use Finger\Tests\Backoffice\Products\Domain\ProductPriceMother;

final class CreateProductCommandMother
{
    public static function create(
        ?ProductId $id = null,
        ?ProductName $name = null,
        ?ProductDescription $description = null,
        ?ProductPrice $price = null
    ): CreateProductCommand {
        return new CreateProductCommand(
            $id?->value() ?? ProductIdMother::create()->value(),
            $name?->value() ?? ProductNameMother::create()->value(),
            $description?->value() ?? ProductDescriptionMother::create()->value(),
            $price?->amount() ?? ProductPriceMother::create()->amount(),
            $price?->currency() ?? ProductPriceMother::create()->currency()
        );
    }

    public static function withId(string $id): CreateProductCommand
    {
        return self::create(id: ProductIdMother::create($id));
    }

    public static function withName(string $name): CreateProductCommand
    {
        return self::create(name: ProductNameMother::create($name));
    }

    public static function withPrice(float $price, string $currency = 'USD'): CreateProductCommand
    {
        return self::create(price: ProductPriceMother::create($price, $currency));
    }

    public static function expensive(): CreateProductCommand
    {
        return self::create(price: ProductPriceMother::expensive());
    }

    public static function cheap(): CreateProductCommand
    {
        return self::create(price: ProductPriceMother::cheap());
    }
}
