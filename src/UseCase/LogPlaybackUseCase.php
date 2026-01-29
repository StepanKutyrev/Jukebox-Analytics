<?php

declare(strict_types=1);

namespace App\UseCase;

use App\DTO\CreatePlaybackLogDTO;
use App\Entity\PlaybackLog;
use App\Exception\ResourceNotFoundException;
use App\Repository\TrackRepository;
use App\Service\StatisticsService;

class LogPlaybackUseCase
{
    public function __construct(
        private readonly TrackRepository $trackRepository,
        private readonly StatisticsService $statisticsService
    ) {
    }

    public function execute(CreatePlaybackLogDTO $dto): PlaybackLog
    {
        $track = $this->trackRepository->find($dto->track_id);
        if ($track === null) {
            throw new ResourceNotFoundException('Track not found');
        }

        return $this->statisticsService->logPlayback($track, $dto->amount_paid);
    }
}
