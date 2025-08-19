<?php

declare(strict_types=1);

namespace Finger\Tests\Auth\Users\Application\Create;

use Finger\Auth\Users\Application\Create\CreateUserCommandHandler;
use Finger\Auth\Users\Application\Create\UserCreator;
use Finger\Auth\Users\Domain\UserAlreadyExists;
use Finger\Tests\Auth\Users\AuthUsersModuleUnitTestCase;
use Finger\Tests\Auth\Users\Domain\UserEmailMother;
use Finger\Tests\Auth\Users\Domain\UserIdMother;
use Finger\Tests\Auth\Users\Domain\UserMother;
use Finger\Tests\Auth\Users\Domain\UserNameMother;
use Finger\Tests\Auth\Users\Domain\UserWasCreatedDomainEventMother;
use PHPUnit\Framework\Attributes\Test;

final class CreateUserCommandHandlerTest extends AuthUsersModuleUnitTestCase
{
    private CreateUserCommandHandler|null $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = new CreateUserCommandHandler(new UserCreator($this->repository(), $this->eventBus()));
    }

    #[Test]
    public function it_should_create_a_valid_user(): void
    {
        $command = CreateUserCommandMother::create();

        $user = UserMother::fromRequest($command);
        $domainEvent = UserWasCreatedDomainEventMother::fromUser($user);

        $this->shouldCheckEmailExists($user->email(), false);
        $this->shouldSaveAnyUser();
        $this->shouldPublishDomainEvent($domainEvent);

        $this->dispatch($command, $this->handler);
    }

    #[Test]
    public function it_should_throw_an_exception_when_the_user_already_exists(): void
    {
        $this->expectException(UserAlreadyExists::class);

        $command = CreateUserCommandMother::create();
        $user = UserMother::fromRequest($command);

        $this->shouldCheckEmailExists($user->email(), true);

        $this->dispatch($command, $this->handler);
    }

}
