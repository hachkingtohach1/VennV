<?php

namespace hachkingtohach1\vennv\utils;

final class FakeMapViolation{

    private int|float $maxHandler = 0;
    private int|float $handlerVl = 0;
    private int|float $handlerTicks = 0;
    private int|float $maxTicks = 0;

    public function getMaxViolation() : int|float{
        return $this->maxHandler;
    }

    public function getViolations() : int|float{
        return $this->handlerVl;
    }

    public function getTicks() : int|float{
        return microtime(true) - $this->handlerTicks;
    }

    public function getMaxTicks() : int|float{
        return $this->maxTicks;
    }

    public function setMaxViolation(int|float $vl) : void{
        $this->maxHandler = $vl;
    }

    public function setViolation(int|float $vl) : void{
        $this->handlerVl = $vl;
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

    public function addViolation(int|float $vl) : void{
        $this->handlerVl += $vl;
    }

    public function debugTicks() : void{
        $diffT = microtime(true) - $this->handlerTicks;
        if($diffT >= $this->maxTicks){
            $this->handlerTicks = microtime(true);
            $this->handlerVl = 0;
        }
    }

    public function handleViolation(int|float $vl = 1) : bool{
        $this->handlerVl += $vl;
        $diffT = microtime(true) - $this->handlerTicks;
        if($diffT >= $this->maxTicks){
            $result = false;
            if($this->handlerVl >= $this->maxHandler){
                $result = true;
            }
            $this->handlerTicks = microtime(true);
            $this->handlerVl = 0;
            return $result;
        }
        return false;
    }
}