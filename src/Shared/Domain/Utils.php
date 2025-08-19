<?php

declare(strict_types=1);

namespace Finger\Shared\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class Utils
{
    public static function uuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    public static function dateTimeImmutable(?string $datetime = null): DateTimeImmutable
    {
        return new DateTimeImmutable($datetime ?? 'now');
    }

    public static function jsonEncode(array $values): string
    {
        return json_encode($values, JSON_THROW_ON_ERROR);
    }

    public static function jsonDecode(string $json): array
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (null === $data && JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException('Unable to parse response body into JSON: ' . json_last_error());
        }

        return $data;
    }

    public static function toSnakeCase(string $text): string
    {
        return ctype_lower($text) ? $text : strtolower(preg_replace('/([^A-Z\s])([A-Z])/', '$1_$2', $text));
    }

    public static function toCamelCase(string $text): string
    {
        return lcfirst(str_replace('_', '', ucwords($text, '_')));
    }

    public static function extractClassName(object $object): string
    {
        $reflect = new \ReflectionClass($object);

        return $reflect->getShortName();
    }
}
