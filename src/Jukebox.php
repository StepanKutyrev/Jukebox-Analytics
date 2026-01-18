<?php

namespace App;

use App\Coin\CoinAcceptor;
use App\Media\MediaInterface;
use App\State\StateInterface;
use App\State\IdleState;
use App\State\PlayingState;
use App\State\CoinInsertedState;

class Jukebox
{
    private ?MediaInterface $selected = null;
    private StateInterface $state;

    public function __construct(
        private SongRepository $repository,
        private CoinAcceptor $acceptor
    ) {
        $this->state = new IdleState();
    }

    public function listSongs(): void
    {
        $songs = $this->repository->all();
        $counter = 1;
        for ($i = 0; $i < count($songs); $i++) {
            $song = $songs[$i];
            $title = $song->getTitle();
            $price = $song->getPrice();
            echo $counter . ". " . $title . " (" . $price . ")\n";
            $counter = $counter + 1;
        }
    }

    public function select(int $index): void
    {
        $realIndex = $index - 1;
        $song = $this->repository->get($realIndex);
        $this->selected = $song;
        
        $title = $song->getTitle();
        $price = $song->getPrice();
        echo "Selected: " . $title . " (" . $price . ")\n";
        
        $currentBalance = $this->acceptor->getBalance();
        $songPrice = $song->getPrice();
        
        if ($currentBalance >= $songPrice) {
            $playingState = new PlayingState();
            $this->state = $playingState;
            $this->state->handle($this);
        } else {
            if ($currentBalance > 0) {
                $coinState = new CoinInsertedState();
                $this->state = $coinState;
                $this->state->handle($this);
            }
        }
    }

    public function insertCoin(float $coin): void
    {
        if ($this->selected == null) {
            throw new \RuntimeException("Please select a song first");
        }
        
        $this->acceptor->insert($coin);
        
        $balanceNow = $this->acceptor->getBalance();
        $neededPrice = $this->selected->getPrice();
        
        if ($balanceNow >= $neededPrice) {
            $this->state = new PlayingState();
            $this->state->handle($this);
        } else {
            $this->state = new CoinInsertedState();
            $this->state->handle($this);
        }
    }

    public function playSelected(): void
    {
        if ($this->selected == null) {
            return;
        }
        
        $this->selected->play();
        
        $songPrice = $this->selected->getPrice();
        $balanceBefore = $this->acceptor->getBalance();
        $changeAmount = $balanceBefore - $songPrice;
        
        $this->acceptor->deduct($songPrice);
        
        if ($changeAmount > 0) {
            $roundedChange = round($changeAmount, 2);
            echo "Change remaining: " . $roundedChange . "\n";
        }
        
        $this->selected = null;
        $idle = new IdleState();
        $this->state = $idle;
        $this->state->handle($this);
    }
    
    public function getSelected(): ?MediaInterface
    {
        return $this->selected;
    }

    public function getBalance(): float
    {
        $balance = $this->acceptor->getBalance();
        return $balance;
    }
}
