<?php

declare(strict_types=1);

namespace Finger\Shared\Infrastructure\Bus\Event;

use Finger\Shared\Domain\Bus\Event\DomainEvent;
use Finger\Shared\Domain\Bus\Event\EventBus;

final class InMemoryEventBus implements EventBus
{
    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            // For now, just log the events
            error_log("Event published: " . get_class($event));
        }
    }
}
