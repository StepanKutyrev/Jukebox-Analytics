<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Coin\CoinValidator;
use App\Coin\CoinAcceptor;
use App\SongRepository;
use App\Jukebox;
use App\Media\Track;

class JukeboxTest extends TestCase
{
    public function testCoinValidation()
    {
        $validator = new CoinValidator();

        $validCoin = 0.25;
        $isValidResult = $validator->isValid($validCoin);
        $this->assertTrue($isValidResult);
        
        $invalidCoin = 0.03;
        $isInvalidResult = $validator->isValid($invalidCoin);
        $this->assertFalse($isInvalidResult);
    }

    public function testJukeboxPlayFlow()
    {
        $track1 = new Track('Queen', 'Bohemian Rhapsody', 1.50);
        $songs = [$track1];
        
        $repository = new SongRepository($songs);
        $validator = new CoinValidator();
        $acceptor = new CoinAcceptor($validator);
        $jukebox = new Jukebox($repository, $acceptor);

        $trackNumber = 1;
        $jukebox->select($trackNumber);
        
        $firstCoin = 1.00;
        $jukebox->insertCoin($firstCoin);
        
        $this->expectOutputRegex('/Playing Queen — Bohemian Rhapsody/');
        
        $secondCoin = 0.50;
        $jukebox->insertCoin($secondCoin);
    }

    public function testChangeIsPreservedAfterPlaying()
    {
        $track1 = new Track('Queen', 'Bohemian Rhapsody', 1.50);
        $songs = [$track1];
        
        $repository = new SongRepository($songs);
        $validator = new CoinValidator();
        $acceptor = new CoinAcceptor($validator);
        $jukebox = new Jukebox($repository, $acceptor);

        $jukebox->select(1);
        $jukebox->insertCoin(1.00);
        $jukebox->insertCoin(1.00);
        
        $this->expectOutputRegex('/Change remaining: 0\.5/');
        
        $balanceAfterPlay = $jukebox->getBalance();
        $expectedBalance = 0.5;
        $this->assertEquals($expectedBalance, $balanceAfterPlay);
    }

    public function testCanChangeSongAfterCoinsInserted()
    {
        $track1 = new Track('Queen', 'Bohemian Rhapsody', 1.50);
        $track2 = new Track('AC/DC', 'Back In Black', 1.20);
        $songs = [$track1, $track2];
        
        $repository = new SongRepository($songs);
        $validator = new CoinValidator();
        $acceptor = new CoinAcceptor($validator);
        $jukebox = new Jukebox($repository, $acceptor);

        $jukebox->select(1);
        $jukebox->insertCoin(1.00);
        
        $jukebox->select(2);
        $jukebox->insertCoin(0.25);
        
        $this->expectOutputRegex('/Playing AC\/DC — Back In Black/');
        
        $balanceAfterPlay = $jukebox->getBalance();
        $expectedBalance = 0.05;
        $this->assertEquals($expectedBalance, $balanceAfterPlay);
    }

    public function testCanSelectNewSongAfterPlaying()
    {
        $track1 = new Track('Queen', 'Bohemian Rhapsody', 1.50);
        $track2 = new Track('AC/DC', 'Back In Black', 1.20);
        $songs = [$track1, $track2];
        
        $repository = new SongRepository($songs);
        $validator = new CoinValidator();
        $acceptor = new CoinAcceptor($validator);
        $jukebox = new Jukebox($repository, $acceptor);

        $jukebox->select(1);
        $jukebox->insertCoin(1.00);
        $jukebox->insertCoin(0.50);
        
        $selectedAfterPlay = $jukebox->getSelected();
        $this->assertNull($selectedAfterPlay);
        
        $jukebox->select(2);
        $selectedAfterNewSelection = $jukebox->getSelected();
        $this->assertNotNull($selectedAfterNewSelection);
        
        $selectedTitle = $selectedAfterNewSelection->getTitle();
        $expectedTitle = "AC/DC — Back In Black";
        $this->assertEquals($expectedTitle, $selectedTitle);
    }

    public function testInvalidCoinThrowsException()
    {
        $validator = new CoinValidator();
        $acceptor = new CoinAcceptor($validator);

        $this->expectException(\InvalidArgumentException::class);
        $acceptor->insert(0.99);
    }
}
