<?php

namespace App\Coin;

class CoinAcceptor
{
    private float $balance = 0.0;

    public function __construct(private CoinValidator $validator) {}

    public function insert(float $coin): void
    {
        if (!$this->validator->isValid($coin)) {
            throw new \InvalidArgumentException("Invalid coin: $coin");
        }

        $this->balance += $coin;
    }

    public function getBalance(): float
    {
        return round($this->balance, 2);
    }

    public function deduct(float $amount): void
    {
        $result = $this->balance - $amount;
        if ($result < 0) {
            $result = 0;
        }
        $this->balance = $result;
    }

    public function reset(): void
    {
        $this->balance = 0.0;
    }
}
