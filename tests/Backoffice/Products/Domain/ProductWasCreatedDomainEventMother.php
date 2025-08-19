<?php

declare(strict_types=1);

namespace Finger\Tests\Backoffice\Products\Domain;

use Finger\Backoffice\Products\Domain\Product;
use Finger\Backoffice\Products\Domain\ProductDescription;
use Finger\Backoffice\Products\Domain\ProductId;
use Finger\Backoffice\Products\Domain\ProductName;
use Finger\Backoffice\Products\Domain\ProductPrice;
use Finger\Backoffice\Products\Domain\ProductWasCreated;

final class ProductWasCreatedDomainEventMother
{
    public static function create(
        ?ProductId $id = null,
        ?ProductName $name = null,
        ?ProductDescription $description = null,
        ?ProductPrice $price = null
    ): ProductWasCreated {
        return new ProductWasCreated(
            $id?->value() ?? ProductIdMother::create()->value(),
            $name?->value() ?? ProductNameMother::create()->value(),
            $description?->value() ?? ProductDescriptionMother::create()->value(),
            $price?->amount() ?? ProductPriceMother::create()->amount(),
            $price?->currency() ?? ProductPriceMother::create()->currency()
        );
    }

    public static function fromProduct(Product $product): ProductWasCreated
    {
        return self::create(
            $product->id(),
            $product->name(),
            $product->description(),
            $product->price()
        );
    }
}
