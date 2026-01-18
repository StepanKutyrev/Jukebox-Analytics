<?php

namespace App\Coin;

class CoinValidator
{
    private const ALLOWED = [0.01, 0.05, 0.10, 0.25, 0.50, 1.00];

    public function isValid(float $coin): bool
    {
        $allowed = self::ALLOWED;
        foreach ($allowed as $allowedCoin) {
            if ($coin == $allowedCoin) {
                return true;
            }
        }
        return false;
    }
}
