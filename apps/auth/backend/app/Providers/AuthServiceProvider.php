<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Providers;

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
use Finger\Shared\Infrastructure\Persistence\MongoDB\MongoClientFactory;
use Illuminate\Support\ServiceProvider;
use MongoDB\Database;

final class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Database
        $this->app->singleton(Database::class, function ($app) {
            // Use the simplified method that works with our current setup
            return MongoClientFactory::createDatabase();
        });

        // Event Bus
        $this->app->singleton(EventBus::class, function ($app) {
            return new InMemoryEventBus();
        });

        // Repository
        $this->app->bind(UserRepository::class, function ($app) {
            return new MongoUserRepository($app->make(Database::class));
        });

        // JWT Services
        $this->app->singleton(JwtTokenService::class, function ($app) {
            return new JwtTokenService(
                env('JWT_SECRET', 'your-secret-key-change-in-production'),
                'HS256',
                3600,  // 1 hour for access token
                604800 // 7 days for refresh token
            );
        });

        $this->app->bind(JwtTokenManager::class, function ($app) {
            return new JwtTokenManager(
                $app->make(JwtTokenService::class),
                $app->make(UserRepository::class)
            );
        });

        // Application Services
        $this->app->bind(UserCreator::class, function ($app) {
            return new UserCreator(
                $app->make(UserRepository::class),
                $app->make(EventBus::class)
            );
        });

        $this->app->bind(UserAuthenticator::class, function ($app) {
            return new UserAuthenticator(
                $app->make(UserRepository::class),
                $app->make(JwtTokenManager::class)
            );
        });

        // Command Handlers
        $this->app->bind(CreateUserCommandHandler::class, function ($app) {
            return new CreateUserCommandHandler($app->make(UserCreator::class));
        });

        $this->app->bind(AuthenticateUserCommandHandler::class, function ($app) {
            return new AuthenticateUserCommandHandler($app->make(UserAuthenticator::class));
        });

        // Command Bus
        $this->app->singleton(CommandBus::class, function ($app) {
            $handlers = [
                $app->make(CreateUserCommandHandler::class),
                $app->make(AuthenticateUserCommandHandler::class),
            ];

            return new SymfonyCommandBus($handlers);
        });
    }

    public function boot(): void
    {
        //
    }
}
