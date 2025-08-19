<?php

declare(strict_types=1);

namespace Finger\Apps\Backoffice\Backend\Controller\Product;

use Finger\Backoffice\Products\Application\Search\SearchProductsQuery;
use Finger\Shared\Domain\Bus\Query\QueryBus;
use Finger\Shared\Infrastructure\Http\Route;
use Finger\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/products', 'GET', 'search_products')]
final class ProductGetController extends ApiController
{
    public function __construct(
        private QueryBus $queryBus
    ) {
        parent::__construct(null, $queryBus);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        $limit = $request->query->get('limit') ? (int) $request->query->get('limit') : null;
        $offset = $request->query->get('offset') ? (int) $request->query->get('offset') : null;

        $query = new SearchProductsQuery($name, $limit, $offset);

        try {
            $response = $this->ask($query);

            return new JsonResponse($response->toArray());

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Unable to search products',
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
