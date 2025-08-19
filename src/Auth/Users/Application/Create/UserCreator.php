<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Application\Create;

use Finger\Auth\Shared\Domain\Users\UserId;
use Finger\Auth\Users\Domain\User;
use Finger\Auth\Users\Domain\UserAlreadyExists;
use Finger\Auth\Users\Domain\UserEmail;
use Finger\Auth\Users\Domain\UserName;
use Finger\Auth\Users\Domain\UserPassword;
use Finger\Auth\Users\Domain\UserRepository;
use Finger\Shared\Domain\Bus\Event\EventBus;

final class UserCreator
{
    private UserRepository $repository;
    private EventBus $bus;

    public function __construct(UserRepository $repository, EventBus $bus)
    {
        $this->repository = $repository;
        $this->bus = $bus;
    }

    public function __invoke(string $id, string $email, string $password, string $name): void
    {
        $userEmail = new UserEmail($email);

        if ($this->repository->existsByEmail($userEmail)) {
            throw new UserAlreadyExists($email);
        }

        $user = User::create(
            new UserId($id),
            $userEmail,
            UserPassword::fromPlainPassword($password),
            new UserName($name)
        );

        $this->repository->save($user);

        $this->bus->publish(...$user->pullDomainEvents());
    }
}
