<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Http\Controllers;

use Finger\Auth\Shared\Infrastructure\Jwt\JwtTokenManager;
use Finger\Auth\Users\Application\Authenticate\AuthenticateUserCommand;
use Finger\Auth\Users\Application\Create\CreateUserCommand;
use Finger\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Ramsey\Uuid\Uuid;

final class AuthController extends Controller
{
    public function __construct(
        private CommandBus $commandBus,
        private JwtTokenManager $tokenManager
    ) {}

    public function login(Request $request): JsonResponse
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
            'data' => $tokens
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'name' => 'required|string|max:255'
        ]);

        $userId = Uuid::uuid4()->toString();

        $command = new CreateUserCommand(
            $userId,
            $request->input('email'),
            $request->input('password'),
            $request->input('name')
        );

        $this->commandBus->dispatch($command);

        return response()->json([
            'message' => 'User registered successfully',
            'id' => $userId
        ], 201);
    }

    public function refresh(Request $request): JsonResponse
    {
        $this->validate($request, [
            'refresh_token' => 'required|string'
        ]);

        try {
            $tokens = $this->tokenManager->refreshTokenPair(
                $request->input('refresh_token')
            );

            return response()->json([
                'message' => 'Token refresh successful',
                'data' => $tokens
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 401);
        }
    }
}
