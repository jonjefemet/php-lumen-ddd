<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Application\Create;

use Finger\Shared\Domain\Bus\Command\Command;

final class CreateUserCommand implements Command
{
    private string $id;
    private string $email;
    private string $password;
    private string $name;

    public function __construct(string $id, string $email, string $password, string $name)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function name(): string
    {
        return $this->name;
    }
}
