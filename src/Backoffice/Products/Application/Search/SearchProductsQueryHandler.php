<?php

declare(strict_types=1);

namespace Finger\Backoffice\Products\Application\Search;

use Finger\Shared\Domain\Bus\Query\QueryHandler;
use Finger\Shared\Domain\Bus\Query\Response;

final class SearchProductsQueryHandler implements QueryHandler
{
    public function __construct(
        private ProductSearcher $searcher
    ) {
    }

    public function __invoke(SearchProductsQuery $query): Response
    {
        $products = $this->searcher->__invoke(
            $query->name(),
            $query->limit(),
            $query->offset()
        );

        return ProductsResponse::fromProducts($products);
    }
}
