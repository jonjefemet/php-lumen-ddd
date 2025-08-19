<?php

declare(strict_types=1);

namespace Finger\Backoffice\Products\Application\Create;

use Finger\Backoffice\Products\Domain\Product;
use Finger\Backoffice\Products\Domain\ProductAlreadyExists;
use Finger\Backoffice\Products\Domain\ProductDescription;
use Finger\Backoffice\Products\Domain\ProductId;
use Finger\Backoffice\Products\Domain\ProductName;
use Finger\Backoffice\Products\Domain\ProductPrice;
use Finger\Backoffice\Products\Domain\ProductRepository;
use Finger\Shared\Domain\Bus\Event\EventBus;

final class ProductCreator
{
    public function __construct(
        private ProductRepository $repository,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(
        string $id,
        string $name,
        string $description,
        float $price,
        string $currency = 'USD'
    ): void {
        $productId = new ProductId($id);

        if ($this->repository->existsWithId($productId)) {
            throw new ProductAlreadyExists($id);
        }

        $product = Product::create(
            $productId,
            new ProductName($name),
            new ProductDescription($description),
            new ProductPrice($price, $currency)
        );

        $this->repository->save($product);
        $this->eventBus->publish(...$product->pullDomainEvents());
    }
}
