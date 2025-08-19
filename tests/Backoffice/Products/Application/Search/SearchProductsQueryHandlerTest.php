<?php

declare(strict_types=1);

namespace Finger\Tests\Backoffice\Products\Application\Search;

use Finger\Backoffice\Products\Application\Search\ProductSearcher;
use Finger\Backoffice\Products\Application\Search\ProductsResponse;
use Finger\Backoffice\Products\Application\Search\SearchProductsQueryHandler;
use Finger\Tests\Backoffice\Products\BackofficeProductsModuleUnitTestCase;
use Finger\Tests\Backoffice\Products\Domain\ProductMother;
use PHPUnit\Framework\Attributes\Test;

final class SearchProductsQueryHandlerTest extends BackofficeProductsModuleUnitTestCase
{
    private SearchProductsQueryHandler|null $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = new SearchProductsQueryHandler(new ProductSearcher($this->repository()));
    }

    #[Test]
    public function it_should_find_existing_products(): void
    {
        $query = SearchProductsQueryMother::create();
        $existingProduct = ProductMother::create();
        $anotherExistingProduct = ProductMother::create();
        $existingProducts = [$existingProduct, $anotherExistingProduct];

        $expectedResponse = ProductsResponse::fromProducts($existingProducts);

        $this->shouldSearchAll($existingProducts);

        $this->assertAskResponse($expectedResponse, $query, $this->handler);
    }

    #[Test]
    public function it_should_return_empty_response_when_no_products_exist(): void
    {
        $query = SearchProductsQueryMother::create();
        $emptyProducts = [];

        $expectedResponse = ProductsResponse::fromProducts($emptyProducts);

        $this->shouldSearchAll($emptyProducts);

        $this->assertAskResponse($expectedResponse, $query, $this->handler);
    }
}
