<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Application\Create;

use Finger\Shared\Domain\Bus\Command\CommandHandler;

final class CreateUserCommandHandler implements CommandHandler
{
    private UserCreator $creator;

    public function __construct(UserCreator $creator)
    {
        $this->creator = $creator;
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $this->creator->__invoke(
            $command->id(),
            $command->email(),
            $command->password(),
            $command->name()
        );
    }
}
