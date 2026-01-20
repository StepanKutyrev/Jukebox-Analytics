<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PlaybackLogControllerTest extends WebTestCase
{
    public function testCreateLogWithMissingFields(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/logs',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $response);
    }

    public function testCreateLogWithInvalidTrackId(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/logs',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"track_id": -1, "amount_paid": 2.50}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testCreateLogWithInvalidAmountPaid(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/logs',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"track_id": 1, "amount_paid": -5.00}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testCreateLogWithNonExistentTrack(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/logs',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"track_id": 99999, "amount_paid": 2.50}'
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Track not found', $response['error']);
    }
}
