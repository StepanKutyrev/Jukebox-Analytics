<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdatePriceDTO
{
    #[Assert\NotBlank(message: 'new_price is required')]
    #[Assert\Positive(message: 'new_price must be greater than 0')]
    public ?float $new_price = null;
}
