<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Domain;

use Finger\Shared\Domain\DomainError;

final class InvalidCredentials extends DomainError
{
    public function errorCode(): string
    {
        return 'invalid_credentials';
    }

    protected function errorMessage(): string
    {
        return 'The provided credentials are invalid';
    }
}
