<?php

declare(strict_types=1);

namespace App\UseCase;

use App\DTO\UpdatePriceDTO;
use App\Entity\Track;
use App\Exception\ResourceNotFoundException;
use App\Repository\TrackRepository;
use App\Service\TrackService;

class UpdateTrackPriceUseCase
{
    public function __construct(
        private readonly TrackRepository $trackRepository,
        private readonly TrackService $trackService
    ) {
    }

    public function execute(int $id, UpdatePriceDTO $dto): Track
    {
        $track = $this->trackRepository->find($id);
        if ($track === null) {
            throw new ResourceNotFoundException('Track not found');
        }

        return $this->trackService->updatePrice($track, $dto->new_price);
    }
}
