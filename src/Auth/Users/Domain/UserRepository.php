<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Domain;

use Finger\Auth\Shared\Domain\Users\UserId;

interface UserRepository
{
    public function save(User $user): void;

    public function search(UserId $id): ?User;

    public function searchByEmail(UserEmail $email): ?User;

    public function existsByEmail(UserEmail $email): bool;
}
