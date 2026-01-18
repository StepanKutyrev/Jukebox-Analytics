<?php

namespace App\State;

use App\Jukebox;

class PlayingState implements StateInterface
{
    public function handle(Jukebox $jukebox): void
    {
        $jukebox->playSelected();
    }
}
