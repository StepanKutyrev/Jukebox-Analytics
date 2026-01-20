<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\TopTrackDTO;
use App\Entity\PlaybackLog;
use App\Entity\Track;
use App\Repository\PlaybackLogRepository;

class StatisticsService
{
    public function __construct(
        private readonly PlaybackLogRepository $playbackLogRepository
    ) {
    }

    public function getTopTracks(int $limit = 3): array
    {
        $results = $this->playbackLogRepository->findTopTracks($limit);

        return array_map(
            fn(array $row) => new TopTrackDTO(
                title: $row['title'],
                playCount: (int) $row['playCount']
            ),
            $results
        );
    }

    public function logPlayback(Track $track, float $amountPaid): PlaybackLog
    {
        $log = new PlaybackLog();
        $log->setTrack($track);
        $log->setPlayedAt(new \DateTimeImmutable());
        $log->setAmountPaid(number_format($amountPaid, 2, '.', ''));

        $this->playbackLogRepository->save($log);

        return $log;
    }
}
