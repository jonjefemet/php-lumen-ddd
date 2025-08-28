<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Http\Controllers\V1\Auth;

use Finger\Auth\Shared\Infrastructure\Jwt\JwtTokenManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

final class RefreshTokenController extends Controller
{
    public function __construct(
        private JwtTokenManager $tokenManager
    ) {}

    public function __invoke(Request $request): JsonResponse
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
                'data' => $tokens,
                'version' => 'v1'
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'version' => 'v1'
            ], 401);
        }
    }
}
