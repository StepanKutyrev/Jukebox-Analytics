<?php

declare(strict_types=1);

namespace App\DTO;

class TopTrackDTO
{
    public function __construct(
        public readonly string $title,
        public readonly int $playCount
    ) {
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'play_count' => $this->playCount,
        ];
    }
}
