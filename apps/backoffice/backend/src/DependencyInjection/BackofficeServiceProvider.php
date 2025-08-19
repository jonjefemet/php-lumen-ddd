<?php

declare(strict_types=1);

namespace Finger\Apps\Backoffice\Backend\DependencyInjection;

use Finger\Apps\Backoffice\Backend\Controller\Product\ProductGetController;
use Finger\Apps\Backoffice\Backend\Controller\Product\ProductPostController;

use Finger\Backoffice\Products\Application\Create\CreateProductCommandHandler;
use Finger\Backoffice\Products\Application\Create\ProductCreator;

use Finger\Backoffice\Products\Application\Search\ProductSearcher;
use Finger\Backoffice\Products\Application\Search\SearchProductsQueryHandler;
use Finger\Backoffice\Products\Domain\ProductRepository;

use Finger\Backoffice\Products\Infrastructure\Persistence\MongoProductRepository;
use Finger\Shared\Domain\Bus\Command\CommandBus;
use Finger\Shared\Domain\Bus\Event\EventBus;
use Finger\Shared\Domain\Bus\Query\QueryBus;
use Finger\Shared\Infrastructure\Bus\Command\SymfonyCommandBus;
use Finger\Shared\Infrastructure\Bus\Event\InMemoryEventBus;
use Finger\Shared\Infrastructure\Bus\Query\SymfonyQueryBus;
use Finger\Shared\Infrastructure\DependencyInjection\Container;
use Finger\Shared\Infrastructure\Persistence\MongoDB\MongoClientFactory;
use MongoDB\Database;

final class BackofficeServiceProvider
{
    public static function register(Container $container): void
    {
        // Event Bus
        $container->singleton(EventBus::class, function (Container $container) {
            return new InMemoryEventBus();
        });

        // Database
        $container->singleton(Database::class, function (Container $container) {
            return MongoClientFactory::createDatabase(
                $_ENV['MONGODB_URI'] ?? 'mongodb://mongo:27017',
                $_ENV['BACKOFFICE_DATABASE_NAME'] ?? 'finger_backoffice'
            );
        });

        // Repository bindings
        $container->bind(ProductRepository::class, function (Container $container) {
            return new MongoProductRepository($container->get(Database::class));
        });

        // Application services
        $container->bind(ProductCreator::class, function (Container $container) {
            return new ProductCreator(
                $container->get(ProductRepository::class),
                $container->get(EventBus::class)
            );
        });

        $container->bind(ProductSearcher::class, function (Container $container) {
            return new ProductSearcher($container->get(ProductRepository::class));
        });

        // Command handlers
        $container->bind(CreateProductCommandHandler::class, function (Container $container) {
            return new CreateProductCommandHandler($container->get(ProductCreator::class));
        });

        // Query handlers
        $container->bind(SearchProductsQueryHandler::class, function (Container $container) {
            return new SearchProductsQueryHandler($container->get(ProductSearcher::class));
        });



        // Command Bus with handlers
        $container->singleton(CommandBus::class, function (Container $container) {
            $handlers = [
                $container->get(CreateProductCommandHandler::class),
            ];

            return new SymfonyCommandBus($handlers);
        });

        // Query Bus with handlers
        $container->singleton(QueryBus::class, function (Container $container) {
            $handlers = [
                $container->get(SearchProductsQueryHandler::class),
            ];

            return new SymfonyQueryBus($handlers);
        });

        // Controllers
        $container->bind(ProductPostController::class, function (Container $container) {
            return new ProductPostController($container->get(CommandBus::class));
        });

        $container->bind(ProductGetController::class, function (Container $container) {
            return new ProductGetController($container->get(QueryBus::class));
        });


    }
}
