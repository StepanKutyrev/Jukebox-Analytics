<?php

namespace App\Media;

interface MediaInterface
{
    public function getTitle(): string;
    public function getPrice(): float;
    public function play(): void;
}
