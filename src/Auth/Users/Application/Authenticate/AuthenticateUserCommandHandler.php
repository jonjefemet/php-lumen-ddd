<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Application\Authenticate;

use Finger\Shared\Domain\Bus\Command\CommandHandler;

final class AuthenticateUserCommandHandler implements CommandHandler
{
    private UserAuthenticator $authenticator;

    public function __construct(UserAuthenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function __invoke(AuthenticateUserCommand $command): array
    {
        return $this->authenticator->__invoke(
            $command->email(),
            $command->password()
        );
    }
}
