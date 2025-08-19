<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Domain;

use Finger\Shared\Domain\DomainError;

final class UserNotFound extends DomainError
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;

        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'user_not_found';
    }

    protected function errorMessage(): string
    {
        return sprintf('The user <%s> does not exist', $this->id);
    }
}
