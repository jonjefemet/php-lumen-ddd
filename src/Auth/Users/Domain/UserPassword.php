<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Domain;

use Finger\Shared\Domain\ValueObject\StringValueObject;
use InvalidArgumentException;

final class UserPassword extends StringValueObject
{
    private function __construct(string $value)
    {
        parent::__construct($value);
    }

    public static function fromPlainPassword(string $plainPassword): self
    {
        self::ensureIsValidPassword($plainPassword);
        
        return new self(password_hash($plainPassword, PASSWORD_ARGON2ID));
    }

    public static function fromHashedPassword(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }

    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->value);
    }

    private static function ensureIsValidPassword(string $password): void
    {
        if (strlen($password) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters long');
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $password)) {
            throw new InvalidArgumentException('Password must contain at least one lowercase letter, one uppercase letter, and one digit');
        }
    }
}
