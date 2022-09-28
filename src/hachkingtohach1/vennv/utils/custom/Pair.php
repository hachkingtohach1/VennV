<?php

namespace hachkingtohach1\vennv\utils\custom;

final class Pair{

    private array|int|float $x;
    private array|int|float $y;

    public function set(array|int|float $x, array|int|float $y){
        $this->x = $x; 
        $this->y = $y;
    }

    public function getX() : array|int|float{
        return $this->x;
    }

    public function getY() : array|int|float{
        return $this->y;
    }

    public function setX($x) : void{
        $this->x = $x;
    }

    public function setY($y) : void{
        $this->y = $y;
    }

    public function __toString() : string{
        return "Pair(x=$this->x, y=$this->y)";
    }
}