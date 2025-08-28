<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Http\Controllers\V1\Auth;

use Finger\Auth\Users\Application\Authenticate\AuthenticateUserCommand;
use Finger\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use OpenApi\Attributes as OA;

final class LoginController extends Controller
{
    public function __construct(
        private CommandBus $commandBus
    ) {}

    #[OA\Post(
        path: '/auth/login',
        summary: 'User Login - Authenticate and get JWT tokens',
        tags: ['Authentication']
    )]
    #[OA\RequestBody(
        required: true,
        description: 'User credentials',
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                new OA\Property(property: 'password', type: 'string', minLength: 8, example: 'MySecurePassword123')
            ]
        )
    )]
    #[OA\Response(response: 200, description: 'Authentication successful')]
    #[OA\Response(response: 401, description: 'Invalid credentials')]
    public function __invoke(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $command = new AuthenticateUserCommand(
            $request->input('email'),
            $request->input('password')
        );

        $tokens = $this->commandBus->dispatch($command);

        return response()->json([
            'message' => 'Authentication successful',
            'data' => $tokens,
            'version' => 'v1'
        ]);
    }
}
