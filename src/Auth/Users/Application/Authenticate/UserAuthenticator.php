<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Application\Authenticate;

use Finger\Auth\Shared\Infrastructure\Jwt\JwtTokenManager;
use Finger\Auth\Users\Domain\InvalidCredentials;
use Finger\Auth\Users\Domain\UserEmail;
use Finger\Auth\Users\Domain\UserRepository;

final class UserAuthenticator
{
    private UserRepository $repository;
    private JwtTokenManager $tokenManager;

    public function __construct(UserRepository $repository, JwtTokenManager $tokenManager)
    {
        $this->repository = $repository;
        $this->tokenManager = $tokenManager;
    }

    public function __invoke(string $email, string $password): array
    {
        $user = $this->repository->searchByEmail(new UserEmail($email));

        if (null === $user) {
            throw new InvalidCredentials();
        }

        if (!$user->authenticate($password)) {
            throw new InvalidCredentials();
        }

        // Generate JWT tokens
        return $this->tokenManager->generateTokenPair($user);
    }
}
