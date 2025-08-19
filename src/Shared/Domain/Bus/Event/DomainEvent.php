<?php

declare(strict_types=1);

namespace Finger\Shared\Domain\Bus\Event;

use Finger\Shared\Domain\Utils;
use DateTimeImmutable;

abstract class DomainEvent
{
    private string $aggregateId;
    private string $eventId;
    private DateTimeImmutable $occurredOn;

    public function __construct(string $aggregateId, string $eventId = null, DateTimeImmutable $occurredOn = null)
    {
        $this->aggregateId = $aggregateId;
        $this->eventId = $eventId ?: Utils::uuid();
        $this->occurredOn = $occurredOn ?: Utils::dateTimeImmutable();
    }

    abstract public static function eventName(): string;

    abstract public function toPrimitives(): array;

    abstract public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        DateTimeImmutable $occurredOn
    ): self;

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
