<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Domain;

use DateTimeImmutable;
use Finger\Shared\Domain\Bus\Event\DomainEvent;

final class UserPasswordWasChanged extends DomainEvent
{
    private DateTimeImmutable $updatedAt;

    public function __construct(
        string $aggregateId,
        DateTimeImmutable $updatedAt,
        string $eventId = null,
        DateTimeImmutable $occurredOn = null
    ) {
        $this->updatedAt = $updatedAt;

        parent::__construct($aggregateId, $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'user.password_was_changed';
    }

    public function toPrimitives(): array
    {
        return [
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        DateTimeImmutable $occurredOn
    ): DomainEvent {
        return new self(
            $aggregateId,
            new DateTimeImmutable($body['updated_at']),
            $eventId,
            $occurredOn
        );
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
