<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Controller\Auth;

use Finger\Auth\Users\Application\Authenticate\AuthenticateUserCommand;
use Finger\Shared\Domain\Bus\Command\CommandBus;
use Finger\Shared\Infrastructure\Http\Route;
use Finger\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/auth/login', 'POST', 'auth_login')]

final class LoginPostController extends ApiController
{
    public function __construct(private CommandBus $commandBus)
    {
        parent::__construct($commandBus, null);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $requestData = $this->jsonDecode($request->getContent());

        $command = new AuthenticateUserCommand(
            $requestData['email'] ?? '',
            $requestData['password'] ?? ''
        );

        $this->dispatch($command);

        return new JsonResponse([
            'message' => 'Authentication successful',
            'token' => 'jwt_token_placeholder'
        ]);
    }
}
