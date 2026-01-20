<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\DTO\TopTrackDTO;
use App\Entity\PlaybackLog;
use App\Entity\Track;
use App\Repository\PlaybackLogRepository;
use App\Service\StatisticsService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StatisticsServiceTest extends TestCase
{
    private MockObject&PlaybackLogRepository $playbackLogRepository;
    private StatisticsService $statisticsService;

    protected function setUp(): void
    {
        $this->playbackLogRepository = $this->createMock(PlaybackLogRepository::class);
        $this->statisticsService = new StatisticsService($this->playbackLogRepository);
    }

    public function testGetTopTracksReturnsTopThree(): void
    {
        $mockData = [
            ['trackId' => 1, 'title' => 'Track A', 'playCount' => 100],
            ['trackId' => 2, 'title' => 'Track B', 'playCount' => 75],
            ['trackId' => 3, 'title' => 'Track C', 'playCount' => 50],
        ];

        $this->playbackLogRepository
            ->expects($this->once())
            ->method('findTopTracks')
            ->with(3)
            ->willReturn($mockData);

        $result = $this->statisticsService->getTopTracks(3);

        $this->assertCount(3, $result);
        $this->assertInstanceOf(TopTrackDTO::class, $result[0]);
        $this->assertEquals('Track A', $result[0]->title);
        $this->assertEquals(100, $result[0]->playCount);
        $this->assertEquals('Track B', $result[1]->title);
        $this->assertEquals(75, $result[1]->playCount);
    }

    public function testGetTopTracksWithNoPlaybacks(): void
    {
        $this->playbackLogRepository
            ->expects($this->once())
            ->method('findTopTracks')
            ->with(3)
            ->willReturn([]);

        $result = $this->statisticsService->getTopTracks(3);

        $this->assertCount(0, $result);
        $this->assertIsArray($result);
    }

    public function testGetTopTracksWithCustomLimit(): void
    {
        $mockData = [
            ['trackId' => 1, 'title' => 'Top Track', 'playCount' => 200],
        ];

        $this->playbackLogRepository
            ->expects($this->once())
            ->method('findTopTracks')
            ->with(1)
            ->willReturn($mockData);

        $result = $this->statisticsService->getTopTracks(1);

        $this->assertCount(1, $result);
        $this->assertEquals('Top Track', $result[0]->title);
        $this->assertEquals(200, $result[0]->playCount);
    }

    public function testLogPlaybackCreatesRecord(): void
    {
        $track = $this->createMock(Track::class);

        $this->playbackLogRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (PlaybackLog $log) use ($track) {
                return $log->getTrack() === $track
                    && $log->getAmountPaid() === '2.50'
                    && $log->getPlayedAt() instanceof \DateTimeImmutable;
            }));

        $result = $this->statisticsService->logPlayback($track, 2.50);

        $this->assertInstanceOf(PlaybackLog::class, $result);
        $this->assertEquals('2.50', $result->getAmountPaid());
    }

    public function testTopTrackDTOToArray(): void
    {
        $dto = new TopTrackDTO('Test Song', 42);

        $array = $dto->toArray();

        $this->assertEquals(['title' => 'Test Song', 'play_count' => 42], $array);
    }
}
