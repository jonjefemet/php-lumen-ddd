<?php

declare(strict_types=1);

namespace Finger\Backoffice\Products\Application\Search;

use Finger\Backoffice\Products\Domain\Product;
use Finger\Backoffice\Products\Domain\ProductName;
use Finger\Backoffice\Products\Domain\ProductRepository;

final class ProductSearcher
{
    public function __construct(
        private ProductRepository $repository
    ) {
    }

    /**
     * @return Product[]
     */
    public function __invoke(?string $name = null, ?int $limit = null, ?int $offset = null): array
    {
        if ($name !== null) {
            $products = $this->repository->searchByName(new ProductName($name));
        } else {
            $products = $this->repository->searchAll();
        }

        // Apply pagination if provided
        if ($offset !== null || $limit !== null) {
            $offset = $offset ?? 0;
            $limit = $limit ?? 10;
            $products = array_slice($products, $offset, $limit);
        }

        return $products;
    }
}
