<?php

namespace hachkingtohach1\vennv\api\events;

use pocketmine\event\Event;
use pocketmine\event\Cancellable;

class ViolationEvent extends Event implements Cancellable{

    private bool $isCancelled = false;
    private string $playerName = "";
    private string $cheat = "";    
    private string $verbose = "";
    private int|float $violation = 0;

    public function set(string $playerName, string $cheat, string $verbose, int|float $violation) : void{
        $this->playerName = $playerName;
        $this->cheat = $cheat;
        $this->verbose = $verbose;
        $this->violation = $violation;       
    }

    public function getPlayerName() : string{
        return $this->playerName;
    }

    public function getCheat() : string{
        return $this->cheat;
    }

    public function getVerbose() : string{
        return $this->verbose;
    }

    public function getViolation() : int|float{
        return $this->violation;
    }

    public function isCancelled() : bool{
        return $this->isCancelled;
    }

    public function cancel() : void{
        $this->isCancelled = true;
    }

    public function uncancel() : void{
        $this->isCancelled = false;
    }
}