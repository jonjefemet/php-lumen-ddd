<?php

declare(strict_types=1);

namespace Finger\Shared\Infrastructure\Persistence\MongoDB;

use MongoDB\Client;
use MongoDB\Database;

final class MongoClientFactory
{
    public static function createDatabase(
        string $uri = 'mongodb://mongo:27017',
        string $databaseName = 'finger_auth'
    ): Database {
        $client = new Client($uri, [
            'username' => 'admin',
            'password' => 'secret',
            'authSource' => 'admin'
        ]);
        return $client->selectDatabase($databaseName);
    }
}
