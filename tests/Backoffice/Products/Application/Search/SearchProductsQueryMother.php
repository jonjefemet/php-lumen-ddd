<?php

declare(strict_types=1);

namespace Finger\Tests\Backoffice\Products\Application\Search;

use Faker\Factory;
use Faker\Generator;
use Finger\Backoffice\Products\Application\Search\SearchProductsQuery;

final class SearchProductsQueryMother
{
    private static ?Generator $faker = null;

    private static function faker(): Generator
    {
        return self::$faker ??= Factory::create();
    }

    public static function create(
        ?string $name = null,
        ?int $limit = null,
        ?int $offset = null
    ): SearchProductsQuery {
        return new SearchProductsQuery($name, $limit, $offset);
    }

    public static function withName(string $name): SearchProductsQuery
    {
        return self::create(name: $name);
    }

    public static function withPagination(int $limit, int $offset = 0): SearchProductsQuery
    {
        return self::create(limit: $limit, offset: $offset);
    }

    public static function all(): SearchProductsQuery
    {
        return self::create();
    }

    public static function randomName(): SearchProductsQuery
    {
        return self::create(name: self::faker()->word());
    }
}
