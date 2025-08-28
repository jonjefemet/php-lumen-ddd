<?php

declare(strict_types=1);

namespace Finger\Auth\Shared\Infrastructure\Jwt;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use InvalidArgumentException;

final class JwtTokenService
{
    private string $secret;
    private string $algorithm;
    private int $accessTokenTtl;
    private int $refreshTokenTtl;

    public function __construct(
        string $secret,
        string $algorithm = 'HS256',
        int $accessTokenTtl = 3600, // 1 hour
        int $refreshTokenTtl = 604800 // 7 days
    ) {
        $this->secret = $secret;
        $this->algorithm = $algorithm;
        $this->accessTokenTtl = $accessTokenTtl;
        $this->refreshTokenTtl = $refreshTokenTtl;
    }

    public function generateAccessToken(string $userId, string $email): string
    {
        $now = new DateTimeImmutable();
        $expiresAt = $now->modify('+' . $this->accessTokenTtl . ' seconds');

        $payload = [
            'iss' => 'finger-auth', // issuer
            'aud' => 'finger-app',  // audience
            'iat' => $now->getTimestamp(), // issued at
            'exp' => $expiresAt->getTimestamp(), // expires at
            'sub' => $userId, // subject (user id)
            'email' => $email,
            'type' => 'access'
        ];

        return JWT::encode($payload, $this->secret, $this->algorithm);
    }

    public function generateRefreshToken(string $userId): string
    {
        $now = new DateTimeImmutable();
        $expiresAt = $now->modify('+' . $this->refreshTokenTtl . ' seconds');

        $payload = [
            'iss' => 'finger-auth',
            'aud' => 'finger-app',
            'iat' => $now->getTimestamp(),
            'exp' => $expiresAt->getTimestamp(),
            'sub' => $userId,
            'type' => 'refresh'
        ];

        return JWT::encode($payload, $this->secret, $this->algorithm);
    }

    public function validateToken(string $token): array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, $this->algorithm));
            return (array) $decoded;
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Invalid token: ' . $e->getMessage());
        }
    }

    public function isTokenExpired(array $payload): bool
    {
        $now = new DateTimeImmutable();
        return $now->getTimestamp() > ($payload['exp'] ?? 0);
    }

    public function extractUserIdFromToken(string $token): string
    {
        $payload = $this->validateToken($token);
        
        if (empty($payload['sub'])) {
            throw new InvalidArgumentException('Token does not contain user ID');
        }

        return $payload['sub'];
    }

    public function isRefreshToken(array $payload): bool
    {
        return ($payload['type'] ?? '') === 'refresh';
    }

    public function isAccessToken(array $payload): bool
    {
        return ($payload['type'] ?? '') === 'access';
    }
}
