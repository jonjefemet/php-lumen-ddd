<?php

declare(strict_types=1);

namespace Finger\Tests\Auth\Users\Domain;

use Finger\Auth\Users\Domain\UserEmail;
use Finger\Tests\Shared\Domain\MotherCreator;

final class UserEmailMother
{
    public static function create(?string $value = null): UserEmail
    {
        return new UserEmail($value ?? MotherCreator::random()->unique()->safeEmail());
    }
}
