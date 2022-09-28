<?php

namespace hachkingtohach1\vennv\utils;

final class RequiredPoints{

    private int|float $maxHandler = 0;
    private int|float $handler = 0;
    private int|float $handlerTicks = 0;
    private int|float $maxTicks = 0;

    public function getMax() : int|float{
        return $this->maxHandler;
    }

    public function getPoint() : int|float{
        return $this->handler;
    }

    public function getTicks() : int|float{
        return microtime(true) - $this->handlerTicks;
    }

    public function getMaxTicks() : int|float{
        return $this->maxTicks;
    }

    public function setMax(int|float $p) : void{
        $this->maxHandler = $p;
    }

    public function setPoints(int|float $p) : void{
        $this->handler = $p;
    }

    public function setTicks(int|float $ticks) : void{
        $this->handlerTicks = $ticks;
    }

    public function setMaxTicks(int|float $ticks) : void{
        $this->maxTicks = $ticks;
    }

    public function resetTicks() : void{
        $this->handlerTicks = microtime(true);
    }

    public function resetPoint() : void{
        $this->handler = 0;
    }

    public function debugTicks() : void{
        $diffT = microtime(true) - $this->handlerTicks;
        if($diffT >= $this->maxTicks){
            $this->handlerTicks = microtime(true);
            $this->handler = 0;
        }
    }

    public function addPoint(int|float $point) : void{
        $this->handler += $point;
    }

    public function removePoint(int|float $point) : void{
        $this->handler -= $point;
    }

    public function isFull() : bool{
        return $this->handler >= $this->maxHandler;
    }

    public function isNotFull() : bool{
        return $this->handler < $this->maxHandler;
    }

    public function isZero() : bool{
        return $this->handler <= 0;
    }

    public function isNotZero() : bool{
        return $this->handler > 0;
    }
}