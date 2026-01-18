<?php

namespace App;

use App\Media\MediaInterface;

class SongRepository
{
    public function __construct(private array $songs) {}

    public function all(): array
    {
        return $this->songs;
    }

    public function get(int $index): MediaInterface
    {
        if (!isset($this->songs[$index])) {
            throw new \OutOfBoundsException();
        }
        return $this->songs[$index];
    }
}
