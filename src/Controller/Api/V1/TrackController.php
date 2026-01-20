<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\DTO\UpdatePriceDTO;
use App\Repository\TrackRepository;
use App\Service\TrackService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Tracks')]
class TrackController extends AbstractController
{
    public function __construct(
        private readonly TrackService $trackService,
        private readonly TrackRepository $trackRepository,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/v1/tracks/{id}/price', name: 'api_v1_tracks_update_price', methods: ['PATCH'])]
    #[OA\Patch(
        path: '/api/v1/tracks/{id}/price',
        summary: 'Update track price',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1),
                description: 'The ID of the track'
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['new_price'],
                properties: [
                    new OA\Property(property: 'new_price', type: 'number', format: 'float', example: 2.00)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Price updated successfully'),
            new OA\Response(response: 400, description: 'Validation error'),
            new OA\Response(response: 404, description: 'Track not found')
        ]
    )]
    public function updatePrice(int $id, Request $request): JsonResponse
    {
        $track = $this->trackRepository->find($id);
        if ($track === null) {
            return $this->json(
                ['error' => 'Track not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $dto = $this->serializer->deserialize(
            $request->getContent(),
            UpdatePriceDTO::class,
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

        $updatedTrack = $this->trackService->updatePrice($track, $dto->new_price);

        return $this->json([
            'id' => $updatedTrack->getId(),
            'title' => $updatedTrack->getTitle(),
            'artist' => $updatedTrack->getArtist(),
            'price' => $updatedTrack->getPrice()
        ]);
    }
}
