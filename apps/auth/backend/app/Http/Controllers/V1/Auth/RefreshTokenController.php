<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Http\Controllers\V1\Auth;

use Finger\Auth\Shared\Infrastructure\Jwt\JwtTokenManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use OpenApi\Attributes as OA;

final class RefreshTokenController extends Controller
{
    public function __construct(
        private JwtTokenManager $tokenManager
    ) {}

    #[OA\Post(
        path: '/auth/refresh',
        summary: 'JWT Token Refresh - Get new access token',
        tags: ['Authentication']
    )]
    #[OA\RequestBody(
        required: true,
        description: 'Refresh token data',
        content: new OA\JsonContent(
            required: ['refresh_token'],
            properties: [
                new OA\Property(property: 'refresh_token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...')
            ]
        )
    )]
    #[OA\Response(response: 200, description: 'Token refresh successful')]
    #[OA\Response(response: 401, description: 'Invalid refresh token')]
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
