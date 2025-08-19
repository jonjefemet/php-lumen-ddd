<?php

declare(strict_types=1);

namespace Finger\Auth\Users\Infrastructure\Persistence;

use DateTimeImmutable;
use Finger\Auth\Shared\Domain\Users\UserId;
use Finger\Auth\Users\Domain\User;
use Finger\Auth\Users\Domain\UserEmail;
use Finger\Auth\Users\Domain\UserName;
use Finger\Auth\Users\Domain\UserPassword;
use Finger\Auth\Users\Domain\UserRepository;
use MongoDB\Collection;
use MongoDB\Database;

final class MongoUserRepository implements UserRepository
{
    private Collection $collection;

    public function __construct(Database $database)
    {
        $this->collection = $database->selectCollection('users');
    }

    public function save(User $user): void
    {
        $document = [
            '_id' => $user->id()->value(),
            'email' => $user->email()->value(),
            'password' => $user->password()->value(),
            'name' => $user->name()->value(),
            'created_at' => $user->createdAt()->format('Y-m-d H:i:s'),
            'updated_at' => $user->updatedAt()?->format('Y-m-d H:i:s'),
        ];

        $this->collection->replaceOne(
            ['_id' => $user->id()->value()],
            $document,
            ['upsert' => true]
        );
    }

    public function search(UserId $id): ?User
    {
        $document = $this->collection->findOne(['_id' => $id->value()]);

        if (null === $document) {
            return null;
        }

        // Convert BSONDocument to plain array
        $documentArray = iterator_to_array($document);
        return $this->toDomainEntity($documentArray);
    }

    public function searchByEmail(UserEmail $email): ?User
    {
        $document = $this->collection->findOne(['email' => $email->value()]);

        if (null === $document) {
            return null;
        }

        // Convert BSONDocument to plain array
        $documentArray = iterator_to_array($document);
        return $this->toDomainEntity($documentArray);
    }

    public function existsByEmail(UserEmail $email): bool
    {
        $count = $this->collection->countDocuments(['email' => $email->value()]);

        return $count > 0;
    }

    private function toDomainEntity(array $document): User
    {
        return new User(
            new UserId($document['_id']),
            new UserEmail($document['email']),
            UserPassword::fromHashedPassword($document['password']),
            new UserName($document['name']),
            new DateTimeImmutable($document['created_at']),
            $document['updated_at'] ? new DateTimeImmutable($document['updated_at']) : null
        );
    }
}
