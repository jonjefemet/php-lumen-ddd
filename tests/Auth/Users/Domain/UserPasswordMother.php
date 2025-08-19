<?php

declare(strict_types=1);

namespace Finger\Tests\Auth\Users\Domain;

use Finger\Auth\Users\Domain\UserPassword;
use Finger\Tests\Shared\Domain\RandomElementPicker;

final class UserPasswordMother
{
    public static function create(?string $value = null): UserPassword
    {
        return UserPassword::fromPlainPassword($value ?? self::randomValidPassword());
    }

    public static function fromPlainPassword(string $plainPassword): UserPassword
    {
        return UserPassword::fromPlainPassword($plainPassword);
    }

    private static function randomValidPassword(): string
    {
        // Generate passwords that meet validation criteria
        return RandomElementPicker::from(
            'TestPass123',
            'ValidPass456',
            'SecurePass789',
            'StrongPass012',
            'GoodPass345'
        );
    }
}
