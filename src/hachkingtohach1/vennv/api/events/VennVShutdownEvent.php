<?php

namespace hachkingtohach1\vennv\api\events;

use pocketmine\event\Event;
use pocketmine\event\Cancellable;

class VennVShutdownEvent extends Event implements Cancellable{

    private bool $isCancelled = false;
    private int|float $time = 0;

    public function set(int|float $time) : void{
        $this->time = $time;
    }

    public function getTime() :int|float{
        return $this->time;
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