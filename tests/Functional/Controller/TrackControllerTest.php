<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TrackControllerTest extends WebTestCase
{
    public function testUpdatePriceWithMissingPrice(): void
    {
        $client = static::createClient();

        $client->request(
            'PATCH',
            '/api/v1/tracks/1/price',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $response);
    }

    public function testUpdatePriceWithInvalidPrice(): void
    {
        $client = static::createClient();

        $client->request(
            'PATCH',
            '/api/v1/tracks/1/price',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"new_price": -10.00}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $response);
    }

    public function testUpdatePriceWithZeroPrice(): void
    {
        $client = static::createClient();

        $client->request(
            'PATCH',
            '/api/v1/tracks/1/price',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"new_price": 0}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testUpdatePriceNotFound(): void
    {
        $client = static::createClient();

        $client->request(
            'PATCH',
            '/api/v1/tracks/99999/price',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"new_price": 5.00}'
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Track not found', $response['error']);
    }
}
