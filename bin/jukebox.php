#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Coin\CoinAcceptor;
use App\Coin\CoinValidator;
use App\Jukebox;
use App\SongRepository;

$songs = require __DIR__ . '/../config/songs.php';

$jukebox = new Jukebox(
    new SongRepository($songs),
    new CoinAcceptor(new CoinValidator())
);

$running = true;
while ($running) {
    $balance = $jukebox->getBalance();
    if ($balance > 0) {
        $rounded = round($balance, 2);
        echo "Remaining balance: " . $rounded . "\n";
    }
    
    $jukebox->listSongs();
    
    echo "Choose track number (or 'q' to quit): ";
    $input = fgets(STDIN);
    $choice = trim($input);
    
    $lowerChoice = strtolower($choice);
    if ($lowerChoice == 'q') {
        $running = false;
        break;
    }
    
    try {
        $trackNum = (int)$choice;
        $jukebox->select($trackNum);
        
        $selected = $jukebox->getSelected();
        while ($selected != null) {
            $balanceCheck = $jukebox->getBalance();
            $priceCheck = $selected->getPrice();
            
            if ($balanceCheck < $priceCheck) {
                echo "Insert coin: ";
                $coinInput = fgets(STDIN);
                $coinStr = trim($coinInput);
                
                $lowerCoin = strtolower($coinStr);
                if ($lowerCoin == 'q') {
                    break;
                }
                
                try {
                    $coinValue = (float)$coinStr;
                    $jukebox->insertCoin($coinValue);
                    $selected = $jukebox->getSelected();
                } catch (Exception $e) {
                    $msg = $e->getMessage();
                    echo $msg . "\n";
                }
            } else {
                break;
            }
        }
        
        $currentSelected = $jukebox->getSelected();
        if ($currentSelected == null) {
            echo "\nSong finished! Select another song or 'q' to quit.\n\n";
        }
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
        echo $errorMsg . "\n";
    }
}
