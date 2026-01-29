<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\DTO\CreatePlaybackLogDTO;
use App\Exception\ResourceNotFoundException;
use App\UseCase\LogPlaybackUseCase;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Playback Logs')]
class PlaybackLogController extends AbstractController
{
    public function __construct(
        private readonly LogPlaybackUseCase $logPlaybackUseCase
    ) {
    }

    #[Route('/api/v1/logs', name: 'api_v1_logs_create', methods: ['POST'])]
    #[OA\Post(
        path: '/api/v1/logs',
        summary: 'Register a playback event',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['track_id', 'amount_paid'],
                properties: [
                    new OA\Property(property: 'track_id', type: 'integer', example: 1),
                    new OA\Property(property: 'amount_paid', type: 'number', format: 'float', example: 1.50)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Playback logged successfully'),
            new OA\Response(response: 400, description: 'Validation error'),
            new OA\Response(response: 404, description: 'Track not found')
        ]
    )]
    public function create(
        #[MapRequestPayload] CreatePlaybackLogDTO $dto
    ): JsonResponse {
        try {
            $log = $this->logPlaybackUseCase->execute($dto);

            return $this->json([
                'id' => $log->getId(),
                'message' => 'Playback logged successfully'
            ], Response::HTTP_CREATED);
        } catch (ResourceNotFoundException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
