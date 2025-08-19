<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Application\Authenticate;

use Finger\Auth\Users\Domain\InvalidCredentials;
use Finger\Auth\Users\Domain\UserEmail;
use Finger\Auth\Users\Domain\UserRepository;

final class UserAuthenticator
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $email, string $password): void
    {
        $user = $this->repository->searchByEmail(new UserEmail($email));

        if (null === $user) {
            throw new InvalidCredentials();
        }

        if (!$user->authenticate($password)) {
            throw new InvalidCredentials();
        }

        // Aquí se podría generar un JWT token
        // Para simplificar, solo validamos las credenciales
    }
}
