<?php

declare(strict_types=1);

namespace Finger\Backoffice\Products\Domain;

interface ProductRepository
{
    public function save(Product $product): void;
    
    public function search(ProductId $id): ?Product;
    
    public function searchAll(): array;
    
    public function searchByName(ProductName $name): array;
    
    public function existsWithId(ProductId $id): bool;
}
