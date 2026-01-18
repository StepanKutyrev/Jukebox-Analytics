<?php

namespace App\State;

use App\Jukebox;

class CoinInsertedState implements StateInterface
{
    public function handle(Jukebox $jukebox): void
    {
        $balance = $jukebox->getBalance();
        echo "Balance: " . $balance . "\n";
    }
}
