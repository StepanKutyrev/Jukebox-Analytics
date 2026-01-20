<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePlaybackLogDTO
{
    #[Assert\NotBlank(message: 'track_id is required')]
    #[Assert\Positive(message: 'track_id must be a positive integer')]
    public ?int $track_id = null;

    #[Assert\NotBlank(message: 'amount_paid is required')]
    #[Assert\Positive(message: 'amount_paid must be greater than 0')]
    public ?float $amount_paid = null;
}
