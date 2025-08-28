<?php

declare(strict_types=1);

namespace Finger\Auth\Shared\Infrastructure\Jwt;

use Finger\Auth\Shared\Domain\Users\UserId;
use Finger\Auth\Users\Domain\User;
use Finger\Auth\Users\Domain\UserRepository;
use InvalidArgumentException;

final class JwtTokenManager
{
    private JwtTokenService $tokenService;
    private UserRepository $userRepository;

    public function __construct(JwtTokenService $tokenService, UserRepository $userRepository)
    {
        $this->tokenService = $tokenService;
        $this->userRepository = $userRepository;
    }

    public function generateTokenPair(User $user): array
    {
        $userId = $user->id()->value();
        $email = $user->email()->value();

        return [
            'access_token' => $this->tokenService->generateAccessToken($userId, $email),
            'refresh_token' => $this->tokenService->generateRefreshToken($userId),
            'token_type' => 'Bearer',
            'expires_in' => 3600 // 1 hour
        ];
    }

    public function refreshTokenPair(string $refreshToken): array
    {
        // Validate refresh token
        $payload = $this->tokenService->validateToken($refreshToken);

        // Check if it's actually a refresh token
        if (!$this->tokenService->isRefreshToken($payload)) {
            throw new InvalidArgumentException('Invalid refresh token type');
        }

        // Check if token is expired
        if ($this->tokenService->isTokenExpired($payload)) {
            throw new InvalidArgumentException('Refresh token has expired');
        }

        // Get user from token
        $userId = $payload['sub'];
        $user = $this->userRepository->search(new UserId($userId));

        if (null === $user) {
            throw new InvalidArgumentException('User not found');
        }

        // Generate new token pair
        return $this->generateTokenPair($user);
    }

    public function validateAccessToken(string $accessToken): User
    {
        $payload = $this->tokenService->validateToken($accessToken);

        // Check if it's an access token
        if (!$this->tokenService->isAccessToken($payload)) {
            throw new InvalidArgumentException('Invalid access token type');
        }

        // Check if token is expired
        if ($this->tokenService->isTokenExpired($payload)) {
            throw new InvalidArgumentException('Access token has expired');
        }

        // Get user from token
        $userId = $payload['sub'];
        $user = $this->userRepository->search(new UserId($userId));

        if (null === $user) {
            throw new InvalidArgumentException('User not found');
        }

        return $user;
    }

    public function extractUserFromToken(string $token): ?User
    {
        try {
            $userId = $this->tokenService->extractUserIdFromToken($token);
            return $this->userRepository->search(new UserId($userId));
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }
}
