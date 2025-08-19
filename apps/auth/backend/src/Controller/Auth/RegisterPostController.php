<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Controller\Auth;

use Finger\Auth\Users\Application\Create\CreateUserCommand;
use Finger\Shared\Domain\Bus\Command\CommandBus;
use Finger\Shared\Infrastructure\Http\Route;
use Finger\Shared\Infrastructure\Symfony\ApiController;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/auth/register', 'POST', 'auth_register')]

final class RegisterPostController extends ApiController
{
    public function __construct(private CommandBus $commandBus)
    {
        parent::__construct($commandBus, null);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $requestData = $this->jsonDecode($request->getContent());

        // Generate UUID if not provided
        $userId = $requestData['id'] ?? Uuid::uuid4()->toString();

        $command = new CreateUserCommand(
            $userId,
            $requestData['email'] ?? '',
            $requestData['password'] ?? '',
            $requestData['name'] ?? ''
        );

        $this->dispatch($command);

        return new JsonResponse([
            'message' => 'User registered successfully',
            'id' => $command->id()
        ], JsonResponse::HTTP_CREATED);
    }
}
