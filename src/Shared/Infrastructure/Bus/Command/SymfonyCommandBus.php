<?php

declare(strict_types=1);

namespace Finger\Shared\Infrastructure\Bus\Command;

use Finger\Shared\Domain\Bus\Command\Command;
use Finger\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class SymfonyCommandBus implements CommandBus
{
    private MessageBus $bus;

    public function __construct(iterable $commandHandlers)
    {
        $handlersMap = [];
        
        // Extract command class from handler using reflection
        foreach ($commandHandlers as $handler) {
            $reflection = new \ReflectionClass($handler);
            $method = $reflection->getMethod('__invoke');
            
            if ($method->getNumberOfParameters() === 1) {
                $parameter = $method->getParameters()[0];
                $type = $parameter->getType();
                
                if ($type && $type instanceof \ReflectionNamedType) {
                    $commandClass = $type->getName();
                    $handlersMap[$commandClass] = [$handler];
                }
            }
        }

        $this->bus = new MessageBus([
            new HandleMessageMiddleware(
                new HandlersLocator($handlersMap)
            ),
        ]);
    }

    public function dispatch(Command $command): void
    {
        try {
            $this->bus->dispatch($command);
        } catch (NoHandlerForMessageException) {
            throw new CommandNotRegisteredError($command);
        } catch (HandlerFailedException $error) {
            throw $error->getPrevious() ?? $error;
        }
    }
}
