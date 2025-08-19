<?php

declare(strict_types=1);

namespace Finger\Backoffice\Products\Domain;

final class ProductPrice
{
    private float $amount;
    private string $currency;

    public function __construct(float $amount, string $currency = 'USD')
    {
        $this->ensureAmountIsPositive($amount);
        $this->ensureCurrencyIsValid($currency);
        
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function equals(ProductPrice $other): bool
    {
        return $this->amount === $other->amount && $this->currency === $other->currency;
    }

    private function ensureAmountIsPositive(float $amount): void
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Product price cannot be negative');
        }
    }

    private function ensureCurrencyIsValid(string $currency): void
    {
        $validCurrencies = ['USD', 'EUR', 'GBP', 'JPY', 'MXN'];
        
        if (!in_array($currency, $validCurrencies, true)) {
            throw new \InvalidArgumentException('Invalid currency: ' . $currency);
        }
    }

    public function __toString(): string
    {
        return sprintf('%.2f %s', $this->amount, $this->currency);
    }
}
