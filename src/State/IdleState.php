<?php

namespace App\State;

use App\Jukebox;

class IdleState implements StateInterface
{
    public function handle(Jukebox $jukebox): void
    {
        echo "Waiting for selection...\n";
    }
}
