<?php

declare(strict_types=1);

namespace Finger\Backoffice\Products\Infrastructure\Persistence;

use DateTimeImmutable;
use Finger\Backoffice\Products\Domain\Product;
use Finger\Backoffice\Products\Domain\ProductDescription;
use Finger\Backoffice\Products\Domain\ProductId;
use Finger\Backoffice\Products\Domain\ProductName;
use Finger\Backoffice\Products\Domain\ProductPrice;
use Finger\Backoffice\Products\Domain\ProductRepository;
use MongoDB\Collection;
use MongoDB\Database;

final class MongoProductRepository implements ProductRepository
{
    private Collection $collection;

    public function __construct(Database $database)
    {
        $this->collection = $database->selectCollection('products');
    }

    public function save(Product $product): void
    {
        $document = [
            '_id' => $product->id()->value(),
            'name' => $product->name()->value(),
            'description' => $product->description()->value(),
            'price' => $product->price()->amount(),
            'currency' => $product->price()->currency(),
            'created_at' => $product->createdAt()->format('Y-m-d H:i:s'),
            'updated_at' => $product->updatedAt()?->format('Y-m-d H:i:s'),
        ];

        $this->collection->replaceOne(
            ['_id' => $product->id()->value()],
            $document,
            ['upsert' => true]
        );
    }

    public function search(ProductId $id): ?Product
    {
        $document = $this->collection->findOne(['_id' => $id->value()]);

        if (null === $document) {
            return null;
        }

        // Convert BSONDocument to plain array
        $documentArray = iterator_to_array($document);
        return $this->toDomainEntity($documentArray);
    }

    public function searchAll(): array
    {
        $cursor = $this->collection->find();
        $products = [];

        foreach ($cursor as $document) {
            // Convert BSONDocument to plain array
            $documentArray = iterator_to_array($document);
            $products[] = $this->toDomainEntity($documentArray);
        }

        return $products;
    }

    public function searchByName(ProductName $name): array
    {
        $cursor = $this->collection->find([
            'name' => ['$regex' => $name->value(), '$options' => 'i']
        ]);

        $products = [];

        foreach ($cursor as $document) {
            // Convert BSONDocument to plain array
            $documentArray = iterator_to_array($document);
            $products[] = $this->toDomainEntity($documentArray);
        }

        return $products;
    }

    public function existsWithId(ProductId $id): bool
    {
        $count = $this->collection->countDocuments(['_id' => $id->value()]);
        return $count > 0;
    }

    private function toDomainEntity(array $document): Product
    {
        return new Product(
            new ProductId($document['_id']),
            new ProductName($document['name']),
            new ProductDescription($document['description']),
            new ProductPrice($document['price'], $document['currency']),
            new DateTimeImmutable($document['created_at']),
            $document['updated_at'] ? new DateTimeImmutable($document['updated_at']) : null
        );
    }
}
