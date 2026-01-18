<?php

namespace App\Media;

class VipTrack extends Track
{
    public function play(): void
    {
        echo "VIP ACCESS ENABLED\n";
        parent::play();
    }
}
