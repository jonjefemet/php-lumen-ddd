<?php

declare(strict_types=1);

namespace Finger\Shared\Domain\Bus\Command;

interface CommandBus
{
    public function dispatch(Command $command): mixed;
}
