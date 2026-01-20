<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Service\StatisticsService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Statistics')]
class StatisticsController extends AbstractController
{
    public function __construct(
        private readonly StatisticsService $statisticsService
    ) {
    }

    #[Route('/api/v1/stats/top', name: 'api_v1_stats_top', methods: ['GET'])]
    #[OA\Get(
        path: '/api/v1/stats/top',
        summary: 'Get top 3 most played tracks',
        responses: [
            new OA\Response(
                response: 200, 
                description: 'List of top tracks',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'title', type: 'string', example: 'Song Title'),
                            new OA\Property(property: 'play_count', type: 'integer', example: 5)
                        ]
                    )
                )
            )
        ]
    )]
    public function top(): JsonResponse
    {
        $topTracks = $this->statisticsService->getTopTracks(3);

        return $this->json(
            array_map(fn($dto) => $dto->toArray(), $topTracks)
        );
    }
}
