<?php

declare(strict_types=1);

namespace Finger\Tests\Backoffice\Products\Domain;

use Finger\Backoffice\Products\Application\Create\CreateProductCommand;
use Finger\Backoffice\Products\Domain\Product;
use Finger\Backoffice\Products\Domain\ProductDescription;
use Finger\Backoffice\Products\Domain\ProductId;
use Finger\Backoffice\Products\Domain\ProductName;
use Finger\Backoffice\Products\Domain\ProductPrice;

final class ProductMother
{
    public static function create(
        ?ProductId $id = null,
        ?ProductName $name = null,
        ?ProductDescription $description = null,
        ?ProductPrice $price = null
    ): Product {
        return Product::create(
            $id ?? ProductIdMother::create(),
            $name ?? ProductNameMother::create(),
            $description ?? ProductDescriptionMother::create(),
            $price ?? ProductPriceMother::create()
        );
    }

    public static function withId(string $id): Product
    {
        return self::create(id: ProductIdMother::create($id));
    }

    public static function withName(string $name): Product
    {
        return self::create(name: ProductNameMother::create($name));
    }

    public static function withPrice(float $amount, string $currency = 'USD'): Product
    {
        return self::create(price: ProductPriceMother::create($amount, $currency));
    }

    public static function withDescription(string $description): Product
    {
        return self::create(description: ProductDescriptionMother::create($description));
    }

    public static function expensive(): Product
    {
        return self::create(price: ProductPriceMother::expensive());
    }

    public static function cheap(): Product
    {
        return self::create(price: ProductPriceMother::cheap());
    }

    public static function fromRequest(CreateProductCommand $request): Product
    {
        return self::create(
            ProductIdMother::create($request->id()),
            ProductNameMother::create($request->name()),
            ProductDescriptionMother::create($request->description()),
            ProductPriceMother::create($request->price(), $request->currency())
        );
    }
}
