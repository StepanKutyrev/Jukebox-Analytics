<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Track;
use App\Repository\TrackRepository;

class TrackService
{
    public function __construct(
        private readonly TrackRepository $trackRepository
    ) {
    }

    public function updatePrice(Track $track, float $newPrice): Track
    {
        $track->setPrice(number_format($newPrice, 2, '.', ''));
        $this->trackRepository->save($track);

        return $track;
    }
}
