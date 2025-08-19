<?php

declare(strict_types=1);

namespace Finger\Tests\Auth\Users\Domain;

use Finger\Auth\Shared\Domain\Users\UserId;
use Finger\Auth\Users\Domain\User;
use Finger\Auth\Users\Domain\UserEmail;
use Finger\Auth\Users\Domain\UserName;
use Finger\Auth\Users\Domain\UserWasCreated;

final class UserWasCreatedDomainEventMother
{
    public static function create(
        ?UserId $id = null,
        ?UserEmail $email = null,
        ?UserName $name = null,
        ?\DateTimeImmutable $createdAt = null
    ): UserWasCreated {
        return new UserWasCreated(
            $id?->value() ?? UserIdMother::create()->value(),
            $email?->value() ?? UserEmailMother::create()->value(),
            $name?->value() ?? UserNameMother::create()->value(),
            $createdAt ?? new \DateTimeImmutable()
        );
    }

    public static function fromUser(User $user): UserWasCreated
    {
        return self::create(
            $user->id(),
            $user->email(),
            $user->name(),
            $user->createdAt()
        );
    }
}
