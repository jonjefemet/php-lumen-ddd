<?php

declare(strict_types=1);

namespace Finger\Apps\Backoffice\Backend\Controller\Product;

use Finger\Backoffice\Products\Application\Create\CreateProductCommand;
use Finger\Shared\Domain\Bus\Command\CommandBus;
use Finger\Shared\Infrastructure\Http\Route;
use Finger\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/products', 'POST', 'create_product')]
final class ProductPostController extends ApiController
{
    public function __construct(
        private CommandBus $commandBus
    ) {
        parent::__construct($commandBus, null);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $requestData = $this->jsonDecode($request->getContent());

        $command = new CreateProductCommand(
            $requestData['id'] ?? $this->generateUuid(),
            $requestData['name'] ?? '',
            $requestData['description'] ?? '',
            (float) ($requestData['price'] ?? 0),
            $requestData['currency'] ?? 'USD'
        );

        try {
            $this->dispatch($command);

            return new JsonResponse([
                'message' => 'Product created successfully',
                'id' => $command->id()
            ], JsonResponse::HTTP_CREATED);

        } catch (\InvalidArgumentException $e) {
            return new JsonResponse([
                'error' => 'Validation error',
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Unable to create product',
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    private function generateUuid(): string
    {
        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }
}
