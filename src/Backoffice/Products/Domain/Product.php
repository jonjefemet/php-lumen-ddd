<?php

declare(strict_types=1);

namespace Finger\Backoffice\Products\Domain;

use DateTimeImmutable;
use Finger\Shared\Domain\Aggregate\AggregateRoot;

final class Product extends AggregateRoot
{
    private ProductId $id;
    private ProductName $name;
    private ProductDescription $description;
    private ProductPrice $price;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public function __construct(
        ProductId $id,
        ProductName $name,
        ProductDescription $description,
        ProductPrice $price,
        DateTimeImmutable $createdAt = null,
        DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        ProductId $id,
        ProductName $name,
        ProductDescription $description,
        ProductPrice $price
    ): self {
        $product = new self($id, $name, $description, $price);

        $product->record(new ProductWasCreated(
            $id->value(),
            $name->value(),
            $description->value(),
            $price->amount(),
            $price->currency()
        ));

        return $product;
    }

    public function changePrice(ProductPrice $newPrice): void
    {
        if (!$this->price->equals($newPrice)) {
            $this->price = $newPrice;
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function changeName(ProductName $newName): void
    {
        if (!$this->name->equals($newName)) {
            $this->name = $newName;
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function changeDescription(ProductDescription $newDescription): void
    {
        if (!$this->description->equals($newDescription)) {
            $this->description = $newDescription;
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    // Getters
    public function id(): ProductId
    {
        return $this->id;
    }

    public function name(): ProductName
    {
        return $this->name;
    }

    public function description(): ProductDescription
    {
        return $this->description;
    }

    public function price(): ProductPrice
    {
        return $this->price;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
