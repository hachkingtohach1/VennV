<?php

namespace hachkingtohach1\vennv\utils;

class Location{

	private int|float $x = 0;
	private int|float $y = 0;
	private int|float $z = 0;
	private int|float $yaw = 0;
	private int|float $pitch = 0;
	private bool $onGround = true;
	
	public function set(int|float $x = 0, int|float $y = 0, int|float $z = 0, int|float $yaw = 0, int|float $pitch = 0, bool $onGround = true) : void{
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
		$this->yaw = $yaw;
		$this->pitch = $pitch;
		$this->onGround = $onGround;
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
	
	public function getYaw() : int|float{
		return $this->yaw;
	}
	
	public function getPitch() : int|float{
		return $this->pitch;
	}

	public function isOnGround() : bool{
		return $this->onGround;
	}

	public function add(float|int $x = 0, float|int $y = 0, float|int $z = 0, int|float $yaw = 0, int|float $pitch = 0) : void{
		$this->x += $x;
		$this->y += $y;
		$this->z += $z;
		$this->yaw += $yaw;
		$this->pitch += $pitch;
	}

	public function subtract(float|int $x = 0, float|int $y = 0, float|int $z = 0, int|float $yaw = 0, int|float $pitch = 0) : void{
		$this->x -= $x;
		$this->y -= $y;
		$this->z -= $z;
		$this->yaw -= $yaw;
		$this->pitch -= $pitch;
	}

	public function distanceXZ(Vector $vector) : int|float{
        return sqrt($this->distanceXZSquared($vector));
    }
    
    public function distanceXZSquared(Vector $vector) {
        return pow($this->x - $vector->getX(), 2.0) + pow($this->z - $vector->getZ(), 2.0);
    }

	public function toVector() : Vector{
		return new Vector($this->x, $this->y, $this->z);
	}

	public function __toString() : string{
		return "Location(x=$this->x, y=$this->y, z=$this->z, yaw=$this->yaw, pitch=$this->pitch, onGround=$this->onGround)";
	}

	public function equals(Location $location) : bool{
		return $this->x === $location->getX() && $this->y === $location->getY() && $this->z === $location->getZ() && $this->yaw === $location->getYaw() && $this->pitch === $location->getPitch() && $this->onGround === $location->isOnGround();
	}

	public function hashCode() : string{
		return spl_object_hash($this);
	}
}