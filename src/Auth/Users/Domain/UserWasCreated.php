<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Domain;

use DateTimeImmutable;
use Finger\Shared\Domain\Bus\Event\DomainEvent;

final class UserWasCreated extends DomainEvent
{
    private string $email;
    private string $name;
    private DateTimeImmutable $createdAt;

    public function __construct(
        string $aggregateId,
        string $email,
        string $name,
        DateTimeImmutable $createdAt,
        string $eventId = null,
        DateTimeImmutable $occurredOn = null
    ) {
        $this->email = $email;
        $this->name = $name;
        $this->createdAt = $createdAt;

        parent::__construct($aggregateId, $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'user.was_created';
    }

    public function toPrimitives(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
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
            $body['email'],
            $body['name'],
            new DateTimeImmutable($body['created_at']),
            $eventId,
            $occurredOn
        );
    }

    public function email(): string
    {
        return $this->email;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
