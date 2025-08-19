<?php

declare(strict_types=1);

namespace Finger\Backoffice\Products\Domain;

use Finger\Shared\Domain\Bus\Event\DomainEvent;

final class ProductWasCreated extends DomainEvent
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly float $price,
        public readonly string $currency,
        ?string $eventId = null,
        ?\DateTimeImmutable $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'product.was_created';
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'currency' => $this->currency,
        ];
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        \DateTimeImmutable $occurredOn
    ): DomainEvent {
        return new self(
            $aggregateId,
            $body['name'],
            $body['description'],
            $body['price'],
            $body['currency'],
            $eventId,
            $occurredOn
        );
    }
}
