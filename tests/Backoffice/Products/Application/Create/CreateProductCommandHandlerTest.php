<?php

declare(strict_types=1);

namespace Finger\Tests\Backoffice\Products\Application\Create;

use Finger\Backoffice\Products\Application\Create\CreateProductCommandHandler;
use Finger\Backoffice\Products\Application\Create\ProductCreator;
use Finger\Backoffice\Products\Domain\ProductAlreadyExists;
use Finger\Tests\Backoffice\Products\BackofficeProductsModuleUnitTestCase;
use Finger\Tests\Backoffice\Products\Domain\ProductMother;
use Finger\Tests\Backoffice\Products\Domain\ProductWasCreatedDomainEventMother;
use PHPUnit\Framework\Attributes\Test;

final class CreateProductCommandHandlerTest extends BackofficeProductsModuleUnitTestCase
{
    private CreateProductCommandHandler|null $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = new CreateProductCommandHandler(new ProductCreator($this->repository(), $this->eventBus()));
    }

    #[Test]
    public function it_should_create_a_valid_product(): void
    {
        $command = CreateProductCommandMother::create();

        $product = ProductMother::fromRequest($command);
        $domainEvent = ProductWasCreatedDomainEventMother::fromProduct($product);

        $this->shouldCheckProductExists($product->id(), false);
        $this->shouldSave($product);
        $this->shouldPublishDomainEvent($domainEvent);

        $this->dispatch($command, $this->handler);
    }

    #[Test]
    public function it_should_throw_an_exception_when_the_product_already_exists(): void
    {
        $this->expectException(ProductAlreadyExists::class);

        $command = CreateProductCommandMother::create();
        $product = ProductMother::fromRequest($command);

        $this->shouldCheckProductExists($product->id(), true);

        $this->dispatch($command, $this->handler);
    }
}
