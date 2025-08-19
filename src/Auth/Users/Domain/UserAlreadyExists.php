<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Domain;

use Finger\Shared\Domain\DomainError;

final class UserAlreadyExists extends DomainError
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;

        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'user_already_exists';
    }

    protected function errorMessage(): string
    {
        return sprintf('The user with email <%s> already exists', $this->email);
    }
}
