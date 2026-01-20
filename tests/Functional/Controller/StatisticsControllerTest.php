<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class StatisticsControllerTest extends WebTestCase
{
    public function testGetTopTracksReturnsJsonArray(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/stats/top');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
    }

    public function testGetTopTracksWithEmptyDatabase(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/stats/top');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
    }
}
