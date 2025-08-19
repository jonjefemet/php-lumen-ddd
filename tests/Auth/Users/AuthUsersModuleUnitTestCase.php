<?php

declare(strict_types=1);

namespace Finger\Tests\Auth\Users;

use Finger\Auth\Users\Domain\UserEmail;
use Finger\Auth\Users\Domain\UserRepository;
use Finger\Tests\Shared\Infrastructure\BaseTestCase;
use Mockery\MockInterface;

abstract class AuthUsersModuleUnitTestCase extends BaseTestCase
{
    private UserRepository|MockInterface|null $repository = null;

    protected function shouldSaveAnyUser(): void
    {
        $this->repository()
            ->shouldReceive('save')
            ->once()
            ->andReturnNull();
    }

    protected function shouldCheckEmailExists(UserEmail $email, bool $exists): void
    {
        $this->repository()
            ->shouldReceive('existsByEmail')
            ->with($this->similarTo($email))
            ->once()
            ->andReturn($exists);
    }

    protected function repository(): UserRepository|MockInterface
    {
        return $this->repository ??= $this->mock(UserRepository::class);
    }
}
