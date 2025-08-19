<?php

declare(strict_types=1);

namespace Finger\Backoffice\Products\Domain;

use Finger\Shared\Domain\DomainError;

final class ProductAlreadyExists extends DomainError
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'product_already_exists';
    }

    protected function errorMessage(): string
    {
        return sprintf('Product with id <%s> already exists', $this->id);
    }
}
