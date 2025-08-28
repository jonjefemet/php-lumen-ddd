<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Http\Controllers\V1\Auth;

use Finger\Auth\Users\Application\Create\CreateUserCommand;
use Finger\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Ramsey\Uuid\Uuid;

final class RegisterController extends Controller
{
    public function __construct(
        private CommandBus $commandBus
    ) {}

    public function __invoke(Request $request): JsonResponse
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
            'data' => [
                'id' => $userId,
                'email' => $request->input('email'),
                'name' => $request->input('name')
            ],
            'version' => 'v1'
        ], 201);
    }
}
