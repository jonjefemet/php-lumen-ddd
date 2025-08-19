<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Domain;

use DateTimeImmutable;
use Finger\Auth\Shared\Domain\Users\UserId;
use Finger\Shared\Domain\Aggregate\AggregateRoot;

final class User extends AggregateRoot
{
    private UserId $id;
    private UserEmail $email;
    private UserPassword $password;
    private UserName $name;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public function __construct(
        UserId $id,
        UserEmail $email,
        UserPassword $password,
        UserName $name,
        DateTimeImmutable $createdAt = null,
        DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        UserId $id,
        UserEmail $email,
        UserPassword $password,
        UserName $name
    ): self {
        $user = new self($id, $email, $password, $name);

        $user->record(new UserWasCreated(
            $id->value(),
            $email->value(),
            $name->value(),
            $user->createdAt
        ));

        return $user;
    }

    public function authenticate(string $plainPassword): bool
    {
        return $this->password->verify($plainPassword);
    }

    public function changePassword(UserPassword $newPassword): void
    {
        $this->password = $newPassword;
        $this->updatedAt = new DateTimeImmutable();

        $this->record(new UserPasswordWasChanged(
            $this->id->value(),
            $this->updatedAt
        ));
    }

    // Getters
    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function password(): UserPassword
    {
        return $this->password;
    }

    public function name(): UserName
    {
        return $this->name;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'email' => $this->email->value(),
            'name' => $this->name->value(),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
