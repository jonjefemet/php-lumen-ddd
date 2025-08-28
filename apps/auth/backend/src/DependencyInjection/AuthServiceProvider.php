<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\DependencyInjection;

use Finger\Apps\Auth\Backend\Controller\Auth\LoginPostController;
use Finger\Apps\Auth\Backend\Controller\Auth\RefreshTokenPostController;
use Finger\Apps\Auth\Backend\Controller\Auth\RegisterPostController;
use Finger\Auth\Shared\Infrastructure\Jwt\JwtTokenManager;
use Finger\Auth\Shared\Infrastructure\Jwt\JwtTokenService;
use Finger\Auth\Users\Application\Authenticate\AuthenticateUserCommandHandler;
use Finger\Auth\Users\Application\Authenticate\UserAuthenticator;
use Finger\Auth\Users\Application\Create\CreateUserCommandHandler;
use Finger\Auth\Users\Application\Create\UserCreator;
use Finger\Auth\Users\Domain\UserRepository;
use Finger\Auth\Users\Infrastructure\Persistence\MongoUserRepository;
use Finger\Shared\Domain\Bus\Command\CommandBus;
use Finger\Shared\Domain\Bus\Event\EventBus;
use Finger\Shared\Infrastructure\Bus\Command\SymfonyCommandBus;
use Finger\Shared\Infrastructure\Bus\Event\InMemoryEventBus;
use Finger\Shared\Infrastructure\DependencyInjection\Container;
use Finger\Shared\Infrastructure\Persistence\MongoDB\MongoClientFactory;
use MongoDB\Database;

final class AuthServiceProvider
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
                $_ENV['AUTH_DATABASE_NAME'] ?? 'finger_auth'
            );
        });

        // Repository bindings
        $container->bind(UserRepository::class, function (Container $container) {
            return new MongoUserRepository($container->get(Database::class));
        });

        // JWT Services
        $container->singleton(JwtTokenService::class, function (Container $container) {
            return new JwtTokenService(
                $_ENV['JWT_SECRET'] ?? 'your-secret-key-change-in-production',
                'HS256',
                3600,  // 1 hour for access token
                604800 // 7 days for refresh token
            );
        });

        $container->bind(JwtTokenManager::class, function (Container $container) {
            return new JwtTokenManager(
                $container->get(JwtTokenService::class),
                $container->get(UserRepository::class)
            );
        });

        // Application services
        $container->bind(UserCreator::class, function (Container $container) {
            return new UserCreator(
                $container->get(UserRepository::class),
                $container->get(EventBus::class)
            );
        });

        $container->bind(UserAuthenticator::class, function (Container $container) {
            return new UserAuthenticator(
                $container->get(UserRepository::class),
                $container->get(JwtTokenManager::class)
            );
        });

        // Command handlers
        $container->bind(CreateUserCommandHandler::class, function (Container $container) {
            return new CreateUserCommandHandler($container->get(UserCreator::class));
        });

        $container->bind(AuthenticateUserCommandHandler::class, function (Container $container) {
            return new AuthenticateUserCommandHandler($container->get(UserAuthenticator::class));
        });

        // Command Bus with handlers
        $container->singleton(CommandBus::class, function (Container $container) {
            $handlers = [
                $container->get(CreateUserCommandHandler::class),
                $container->get(AuthenticateUserCommandHandler::class),
            ];

            return new SymfonyCommandBus($handlers);
        });

        // Controllers
        $container->bind(LoginPostController::class, function (Container $container) {
            return new LoginPostController($container->get(CommandBus::class));
        });

        $container->bind(RefreshTokenPostController::class, function (Container $container) {
            return new RefreshTokenPostController($container->get(JwtTokenManager::class));
        });

        $container->bind(RegisterPostController::class, function (Container $container) {
            return new RegisterPostController($container->get(CommandBus::class));
        });
    }
}
