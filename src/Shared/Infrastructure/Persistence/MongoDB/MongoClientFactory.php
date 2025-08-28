<?php

declare(strict_types=1);

namespace Finger\Shared\Infrastructure\Persistence\MongoDB;

use MongoDB\Client;
use MongoDB\Database;

final class MongoClientFactory
{
    public static function createDatabase(
        string $uri = 'mongodb://admin:secret@mongo:27017',
        string $databaseName = 'finger_auth'
    ): Database {
        // Use environment variables with fallbacks
        $actualUri = $_ENV['MONGODB_URI'] ?? $uri;
        $actualDatabase = $_ENV['MONGODB_DATABASE'] ?? $databaseName;

        $client = new Client($actualUri, [
            'authSource' => 'admin'
        ]);
        
        return $client->selectDatabase($actualDatabase);
    }
}
