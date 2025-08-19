<?php

declare(strict_types=1);

namespace Finger\Tests\Auth\Users\Domain;

use Finger\Auth\Shared\Domain\Users\UserId;
use Finger\Tests\Shared\Domain\UuidMother;

final class UserIdMother
{
    public static function create(?string $value = null): UserId
    {
        return new UserId($value ?? UuidMother::create());
    }
}
