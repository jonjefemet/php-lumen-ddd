<?php

declare(strict_types=1);

namespace Finger\Tests\Shared\Infrastructure;

use Faker\Factory;
use Faker\Generator;
use Finger\Shared\Domain\Bus\Command\Command;
use Finger\Shared\Domain\Bus\Event\DomainEvent;
use Finger\Shared\Domain\Bus\Event\EventBus;
use Finger\Shared\Domain\Bus\Query\Query;
use Finger\Shared\Domain\Bus\Query\Response;
use Finger\Tests\Shared\Domain\TestUtils;
use Finger\Tests\Shared\Infrastructure\Mockery\FingerMatcherIsSimilar;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;

abstract class BaseTestCase extends MockeryTestCase
{
    private ?Generator $faker = null;
    private EventBus|MockInterface|null $eventBus = null;

    protected function faker(): Generator
    {
        return $this->faker ??= Factory::create();
    }

    protected function mock(string $className): MockInterface
    {
        return Mockery::mock($className);
    }

    protected function shouldPublishDomainEvent(DomainEvent $domainEvent): void
    {
        $this->eventBus()
            ->shouldReceive('publish')
            ->with($this->similarTo($domainEvent))
            ->once()
            ->andReturnNull();
    }

    protected function shouldNotPublishDomainEvent(): void
    {
        $this->eventBus()
            ->shouldReceive('publish')
            ->withNoArgs()
            ->andReturnNull();
    }

    protected function eventBus(): EventBus|MockInterface
    {
        return $this->eventBus ??= $this->mock(EventBus::class);
    }

    protected function dispatch(Command $command, callable $commandHandler): void
    {
        $commandHandler($command);
    }

    protected function assertAskResponse(Response $expected, Query $query, callable $queryHandler): void
    {
        $actual = $queryHandler($query);
        $this->assertSimilar($expected, $actual);
    }

    protected function assertSimilar(mixed $expected, mixed $actual): void
    {
        TestUtils::assertSimilar($expected, $actual);
    }

    protected function similarTo(mixed $value, float $delta = 0.0): FingerMatcherIsSimilar
    {
        return TestUtils::similarTo($value, $delta);
    }
}
