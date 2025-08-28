<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Controller\Auth;

use Finger\Auth\Shared\Infrastructure\Jwt\JwtTokenManager;
use Finger\Shared\Infrastructure\Http\Route;
use Finger\Shared\Infrastructure\Symfony\ApiController;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/auth/refresh', 'POST', 'auth_refresh')]

final class RefreshTokenPostController extends ApiController
{
    public function __construct(private JwtTokenManager $tokenManager)
    {
        parent::__construct(null, null);
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $requestData = $this->jsonDecode($request->getContent());
            
            $refreshToken = $requestData['refresh_token'] ?? '';
            
            if (empty($refreshToken)) {
                return new JsonResponse([
                    'error' => 'Refresh token is required'
                ], Response::HTTP_BAD_REQUEST);
            }

            $tokens = $this->tokenManager->refreshTokenPair($refreshToken);

            return new JsonResponse([
                'message' => 'Token refresh successful',
                'data' => $tokens
            ]);
            
        } catch (InvalidArgumentException $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
