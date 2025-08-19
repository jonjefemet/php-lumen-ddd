<?php

declare(strict_types=1);

namespace Finger\Backoffice\Products\Application\Search;

use Finger\Backoffice\Products\Domain\Product;
use Finger\Shared\Domain\Bus\Query\Response;

final class ProductsResponse implements Response
{
    public function __construct(
        private array $products
    ) {
    }

    /**
     * Factory method to create ProductsResponse from domain Products
     *
     * @param Product[] $products
     * @return self
     */
    public static function fromProducts(array $products): self
    {
        $productsData = array_map(function (Product $product) {
            return [
                'id' => $product->id()->value(),
                'name' => $product->name()->value(),
                'description' => $product->description()->value(),
                'price' => $product->price()->amount(),
                'currency' => $product->price()->currency(),
                'created_at' => $product->createdAt()->format('Y-m-d H:i:s'),
                'updated_at' => $product->updatedAt()?->format('Y-m-d H:i:s'),
            ];
        }, $products);

        return new self($productsData);
    }

    public function products(): array
    {
        return $this->products;
    }

    public function toArray(): array
    {
        return [
            'products' => array_map(
                fn(array $product) => [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'currency' => $product['currency'],
                    'created_at' => $product['created_at'],
                    'updated_at' => $product['updated_at'],
                ],
                $this->products
            ),
            'total' => count($this->products)
        ];
    }
}
