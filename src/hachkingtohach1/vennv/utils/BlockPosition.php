<?php

namespace hachkingtohach1\vennv\utils;

final class BlockPosition{

    private int|float $x;
    private int|float $y;
    private int|float $z;

    public function set(int|float $x, int|float $y, int|float $z) : void{
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    public function getX() : int|float{
        return $this->x;
    }

    public function getY() : int|float{
        return $this->y;
    }

    public function getZ() : int|float{
        return $this->z;
    }

    public function diff(BlockPosition $blockPosition) :int|float{
        return abs($this->getX() - $blockPosition->getX()) + abs($this->getZ() - $blockPosition->getZ());
    }

    public function equals(Object $object) :bool{
        if($object == $this){
            return true;
        }
        if(!($object instanceof BlockPosition)){
            return false;
        }
        $blockPosition = $object;
        return $blockPosition->canEqual($this) && $this->getX() == $blockPosition->getX() && $this->getY() == $blockPosition->getY() && $this->getZ() == $blockPosition->getZ();
    }

    public function canEqual(Object $object) : bool{
        return $object instanceof BlockPosition;
    }

    public function nearby(BlockPosition $blockPosition) :bool{
        return $this->diff($blockPosition) == 1;
    }

    public function toString() :string{
        return "BlockPosition(x=".$this->getX().", y=".$this->getY().", z=".$this->getZ().")";
    }

    public function hashCode() :int{
        $result = 1;
        $result = 31 * $result + $this->getX();
        $result = 31 * $result + $this->getY();
        $result = 31 * $result + $this->getZ();
        return $result;
    }
}