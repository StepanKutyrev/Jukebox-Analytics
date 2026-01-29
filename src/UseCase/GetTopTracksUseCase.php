<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Service\StatisticsService;

class GetTopTracksUseCase
{
    public function __construct(
        private readonly StatisticsService $statisticsService
    ) {
    }

    public function execute(int $limit = 3): array
    {
        return $this->statisticsService->getTopTracks($limit);
    }
}
