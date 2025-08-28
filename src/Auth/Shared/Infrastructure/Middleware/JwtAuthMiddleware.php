<?php

declare(strict_types=1);

namespace Finger\Auth\Shared\Infrastructure\Middleware;

use Finger\Auth\Shared\Infrastructure\Jwt\JwtTokenManager;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class JwtAuthMiddleware
{
    private JwtTokenManager $tokenManager;

    public function __construct(JwtTokenManager $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    public function handle(Request $request): ?JsonResponse
    {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return new JsonResponse([
                'error' => 'Authorization header missing or invalid'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = substr($authHeader, 7); // Remove 'Bearer ' prefix

        try {
            $user = $this->tokenManager->validateAccessToken($token);
            
            // Add user to request attributes for use in controllers
            $request->attributes->set('authenticated_user', $user);
            
            return null; // Allow request to continue
            
        } catch (InvalidArgumentException $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
