<?php

declare(strict_types=1);

namespace Finger\Tests\Shared\Domain;

final class WordMother
{
    public static function create(): string
    {
        return MotherCreator::random()->word;
    }

    public static function sentence(int $nbWords = 6): string
    {
        return MotherCreator::random()->sentence($nbWords);
    }

    public static function words(int $nb = 3, bool $asText = false): array|string
    {
        return MotherCreator::random()->words($nb, $asText);
    }
}
