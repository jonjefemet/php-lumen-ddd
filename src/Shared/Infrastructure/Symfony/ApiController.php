<?php

declare(strict_types=1);

namespace Finger\Shared\Infrastructure\Symfony;

use Finger\Shared\Domain\Bus\Command\Command;
use Finger\Shared\Domain\Bus\Command\CommandBus;
use Finger\Shared\Domain\Bus\Query\Query;
use Finger\Shared\Domain\Bus\Query\QueryBus;
use Finger\Shared\Domain\Bus\Query\Response;
use Finger\Shared\Domain\Utils;

abstract class ApiController
{
    private ?CommandBus $commandBus;
    private ?QueryBus $queryBus;

    public function __construct(?CommandBus $commandBus = null, ?QueryBus $queryBus = null)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    protected function dispatch(Command $command): mixed
    {
        return $this->commandBus?->dispatch($command);
    }

    protected function ask(Query $query): ?Response
    {
        return $this->queryBus?->ask($query);
    }

    protected function jsonDecode(string $json): array
    {
        return Utils::jsonDecode($json);
    }
}
