<?php

declare(strict_types=1);

namespace Finger\Shared\Infrastructure\Bus\Query;

use Finger\Shared\Domain\Bus\Query\Query;
use Finger\Shared\Domain\Bus\Query\QueryBus;
use Finger\Shared\Domain\Bus\Query\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SymfonyQueryBus implements QueryBus
{
    private MessageBus $bus;

    public function __construct(iterable $queryHandlers)
    {
        $handlersMap = [];
        
        // Extract query class from handler using reflection
        foreach ($queryHandlers as $handler) {
            $reflection = new \ReflectionClass($handler);
            $method = $reflection->getMethod('__invoke');
            
            if ($method->getNumberOfParameters() === 1) {
                $parameter = $method->getParameters()[0];
                $type = $parameter->getType();
                
                if ($type && $type instanceof \ReflectionNamedType) {
                    $queryClass = $type->getName();
                    $handlersMap[$queryClass] = [$handler];
                }
            }
        }

        $this->bus = new MessageBus([
            new HandleMessageMiddleware(
                new HandlersLocator($handlersMap)
            ),
        ]);
    }

    public function ask(Query $query): ?Response
    {
        try {
            $envelope = $this->bus->dispatch($query);
            $handledStamp = $envelope->last(HandledStamp::class);
            
            return $handledStamp ? $handledStamp->getResult() : null;
            
        } catch (NoHandlerForMessageException) {
            throw new QueryNotRegisteredError($query);
        } catch (HandlerFailedException $error) {
            throw $error->getPrevious() ?? $error;
        }
    }
}
