<?php

declare(strict_types=1);

namespace Finger\Backoffice\Products\Application\Search;

use Finger\Shared\Domain\Bus\Query\Query;

final class SearchProductsQuery implements Query
{
    public function __construct(
        private ?string $name = null,
        private ?int $limit = null,
        private ?int $offset = null
    ) {
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function offset(): ?int
    {
        return $this->offset;
    }
}
