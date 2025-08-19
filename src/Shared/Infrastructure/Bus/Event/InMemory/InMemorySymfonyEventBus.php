<?php

declare(strict_types=1);

namespace Finger\Shared\Infrastructure\Bus\Event\InMemory;

use Finger\Shared\Domain\Bus\Event\DomainEvent;
use Finger\Shared\Domain\Bus\Event\EventBus;
use Finger\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class InMemorySymfonyEventBus implements EventBus
{
    private MessageBus $bus;

    public function __construct(iterable $subscribers)
    {
        $this->bus = new MessageBus([
            new HandleMessageMiddleware(
                new HandlersLocator(
                    CallableFirstParameterExtractor::forCallables($subscribers)
                )
            ),
        ]);
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->bus->dispatch($event);
        }
    }
}
