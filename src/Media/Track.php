<?php

namespace App\Media;

class Track implements MediaInterface
{
    public function __construct(
        protected string $artist,
        protected string $title,
        protected float $price
    ) {}

    public function getTitle(): string
    {
        return $this->artist . " — " . $this->title;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function play(): void
    {
        $title = $this->getTitle();
        echo "Playing " . $title . "\n";
        sleep(2);
    }
}
