<?php

declare(strict_types=1);

namespace Finger\Tests\Backoffice\Products;

use Finger\Backoffice\Products\Domain\Product;
use Finger\Backoffice\Products\Domain\ProductId;
use Finger\Backoffice\Products\Domain\ProductRepository;
use Finger\Tests\Shared\Infrastructure\BaseTestCase;
use Mockery\MockInterface;

abstract class BackofficeProductsModuleUnitTestCase extends BaseTestCase
{
    private ProductRepository|MockInterface|null $repository = null;

    protected function shouldSaveAnyProduct(): void
    {
        $this->repository()
            ->shouldReceive('save')
            ->once()
            ->andReturnNull();
    }

    protected function shouldSave(Product $product): void
    {
        $this->repository()
            ->shouldReceive('save')
            ->with($this->similarTo($product))
            ->once()
            ->andReturnNull();
    }

    protected function shouldSearchAll(array $products): void
    {
        $this->repository()
            ->shouldReceive('searchAll')
            ->once()
            ->andReturn($products);
    }

    protected function shouldCheckProductExists(ProductId $id, bool $exists): void
    {
        $this->repository()
            ->shouldReceive('existsWithId')
            ->with($this->similarTo($id))
            ->once()
            ->andReturn($exists);
    }

    protected function repository(): ProductRepository|MockInterface
    {
        return $this->repository ??= $this->mock(ProductRepository::class);
    }
}
