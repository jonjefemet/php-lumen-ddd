<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Http\Controllers\V1\Auth;

use Finger\Auth\Users\Application\Authenticate\AuthenticateUserCommand;
use Finger\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

final class LoginController extends Controller
{
    public function __construct(
        private CommandBus $commandBus
    ) {}

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
