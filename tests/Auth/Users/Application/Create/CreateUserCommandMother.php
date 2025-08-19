<?php

declare(strict_types=1);

namespace Finger\Tests\Auth\Users\Application\Create;

use Finger\Auth\Shared\Domain\Users\UserId;
use Finger\Auth\Users\Application\Create\CreateUserCommand;
use Finger\Auth\Users\Domain\UserEmail;
use Finger\Auth\Users\Domain\UserName;
use Finger\Auth\Users\Domain\UserPassword;
use Finger\Tests\Auth\Users\Domain\UserEmailMother;
use Finger\Tests\Auth\Users\Domain\UserIdMother;
use Finger\Tests\Auth\Users\Domain\UserNameMother;
use Finger\Tests\Auth\Users\Domain\UserPasswordMother;

final class CreateUserCommandMother
{
    public static function create(
        ?UserId $id = null,
        ?UserEmail $email = null,
        ?UserPassword $password = null,
        ?UserName $name = null
    ): CreateUserCommand {
        return new CreateUserCommand(
            $id?->value() ?? UserIdMother::create()->value(),
            $email?->value() ?? UserEmailMother::create()->value(),
            'TestPass123', // Always use a known valid password for commands
            $name?->value() ?? UserNameMother::create()->value()
        );
    }

    public static function withEmail(string $email): CreateUserCommand
    {
        return self::create(email: UserEmailMother::create($email));
    }

    public static function withId(string $id): CreateUserCommand
    {
        return self::create(id: UserIdMother::create($id));
    }

    public static function withCredentials(string $email, string $password): CreateUserCommand
    {
        return self::create(
            email: UserEmailMother::create($email),
            password: UserPasswordMother::fromPlainPassword($password)
        );
    }
}
