<?php

declare(strict_types=1);

namespace Finger\Tests\Auth\Users\Domain;

use Finger\Auth\Users\Domain\UserName;
use Finger\Tests\Shared\Domain\MotherCreator;

final class UserNameMother
{
    public static function create(?string $value = null): UserName
    {
        return new UserName($value ?? MotherCreator::random()->name());
    }
}
