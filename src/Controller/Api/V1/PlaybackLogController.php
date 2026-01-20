<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\DTO\CreatePlaybackLogDTO;
use App\Repository\TrackRepository;
use App\Service\StatisticsService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Playback Logs')]
class PlaybackLogController extends AbstractController
{
    public function __construct(
        private readonly StatisticsService $statisticsService,
        private readonly TrackRepository $trackRepository,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
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
    public function create(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            CreatePlaybackLogDTO::class,
            'json'
        );

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $messages], Response::HTTP_BAD_REQUEST);
        }

        $track = $this->trackRepository->find($dto->track_id);
        if ($track === null) {
            return $this->json(
                ['error' => 'Track not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $log = $this->statisticsService->logPlayback($track, $dto->amount_paid);

        return $this->json([
            'id' => $log->getId(),
            'message' => 'Playback logged successfully'
        ], Response::HTTP_CREATED);
    }
}
