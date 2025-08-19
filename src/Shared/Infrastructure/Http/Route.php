<?php

declare(strict_types=1);

namespace Finger\Shared\Infrastructure\Http;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Route
{
    public function __construct(
        public readonly string $path,
        public readonly string $method = 'GET',
        public readonly ?string $name = null
    ) {
    }
}
