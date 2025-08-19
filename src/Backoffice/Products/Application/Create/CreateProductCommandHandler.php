<?php

declare(strict_types=1);

namespace Finger\Backoffice\Products\Application\Create;

use Finger\Shared\Domain\Bus\Command\CommandHandler;

final class CreateProductCommandHandler implements CommandHandler
{
    public function __construct(
        private ProductCreator $creator
    ) {
    }

    public function __invoke(CreateProductCommand $command): void
    {
        $this->creator->__invoke(
            $command->id(),
            $command->name(),
            $command->description(),
            $command->price(),
            $command->currency()
        );
    }
}
