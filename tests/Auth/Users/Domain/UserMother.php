<?php

declare(strict_types=1);

namespace Finger\Tests\Auth\Users\Domain;

use Finger\Auth\Shared\Domain\Users\UserId;
use Finger\Auth\Users\Application\Create\CreateUserCommand;
use Finger\Auth\Users\Domain\User;
use Finger\Auth\Users\Domain\UserEmail;
use Finger\Auth\Users\Domain\UserName;
use Finger\Auth\Users\Domain\UserPassword;

final class UserMother
{
    public static function create(
        ?UserId $id = null,
        ?UserEmail $email = null,
        ?UserName $name = null,
        ?UserPassword $password = null
    ): User {
        return User::create(
            $id ?? UserIdMother::create(),
            $email ?? UserEmailMother::create(),
            $password ?? UserPasswordMother::create(),
            $name ?? UserNameMother::create()
        );
    }

    public static function withEmail(string $email): User
    {
        return self::create(email: UserEmailMother::create($email));
    }

    public static function withId(string $id): User
    {
        return self::create(id: UserIdMother::create($id));
    }

    public static function withName(string $name): User
    {
        return self::create(name: UserNameMother::create($name));
    }

    public static function withCredentials(string $email, string $password): User
    {
        return self::create(
            email: UserEmailMother::create($email),
            password: UserPasswordMother::create($password)
        );
    }

    public static function fromRequest(CreateUserCommand $request): User
    {
        return self::create(
            UserIdMother::create($request->id()),
            UserEmailMother::create($request->email()),
            UserNameMother::create($request->name()),
            UserPasswordMother::fromPlainPassword($request->password())
        );
    }
}
