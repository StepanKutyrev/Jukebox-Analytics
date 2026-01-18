<?php

namespace App\State;

use App\Jukebox;

interface StateInterface
{
    public function handle(Jukebox $jukebox): void;
}
