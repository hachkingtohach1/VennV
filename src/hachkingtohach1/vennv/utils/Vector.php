<?php

namespace hachkingtohach1\vennv\utils;

class Vector{

    public float|int $x = 0;
	public float|int $y = 0;
	public float|int $z = 0;

	public function set(int|float $x, int|float $y, int|float $z) : void{
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
	}

    public function zero() :void{
		$this->x = 0;
		$this->y = 0;
		$this->z = 0;
	}

	public function getX() :float|int{
		return $this->x;
	}

	public function getY() :float|int{
		return $this->y;
	}

	public function getZ() :float|int{
		return $this->z;
	}

	public function setX(float|int $n) : void{
		$this->x = $n;
	}

	public function setY(float|int $n) : void{
		$this->y = $n;
	}

	public function setZ(float|int $n) : void{
		$this->z = $n;
	}

	public function getFloorX() : int{
		return (int) floor($this->x);
	}

	public function getFloorY() : int{
		return (int) floor($this->y);
	}

	public function getFloorZ() : int{
		return (int) floor($this->z);
	}

	public function add(float|int $x, float|int $y, float|int $z) : Vector{
		$vector = new Vector();
		$vector->set($this->x + $x, $this->y + $y, $this->z + $z);
		return $vector;
	}

	public function addVector(Vector $v) :Vector{
		return $this->add($v->x, $v->y, $v->z);
	}

	public function subtract(float|int $x, float|int $y, float|int $z) : Vector{
		return $this->add(-$x, -$y, -$z);
	}

	public function subtractVector(Vector $v) : Vector{
		return $this->add(-$v->x, -$v->y, -$v->z);
	}

	public function multiply(float $number) : Vector{
		$vector = new Vector();
		$vector->set($this->x * $number, $this->y * $number, $this->z * $number);
		return $vector;
	}

	public function divide(float $number) : Vector{
		$vector = new Vector();
		$vector->set($this->x / $number, $this->y / $number, $this->z / $number);
		return $vector;
	}

	public function ceil() : Vector{
		$vector = new Vector();
		$vector->set((int) ceil($this->x), (int) ceil($this->y), (int) ceil($this->z));
		return $vector;
	}

	public function floor() : Vector{
		$vector = new Vector();
		$vector->set((int) floor($this->x), (int) floor($this->y), (int) floor($this->z));
		return $vector;
	}

	public function round(int $precision = 0, int $mode = PHP_ROUND_HALF_UP) : Vector{
		$vector = new Vector();
		$vector2 = new Vector();
		$vector->set(round($this->x, $precision, $mode), round($this->y, $precision, $mode), round($this->z, $precision, $mode));
		$vector2->set((int) round($this->x, $precision, $mode), (int) round($this->y, $precision, $mode), (int) round($this->z, $precision, $mode));
		return $precision > 0 ? $vector : $vector2;
	}

	public function abs() : Vector{
		$vector = new Vector();
		$vector->set(abs($this->x), abs($this->y), abs($this->z));
		return $vector;
	}

	public function distance(Vector $pos) : float{
		return sqrt($this->distanceSquared($pos));
	}

	public function distanceSquared(Vector $pos) : float{
		return (($this->x - $pos->x) ** 2) + (($this->y - $pos->y) ** 2) + (($this->z - $pos->z) ** 2);
	}

	public function maxPlainDistance(Vector|float $x, float $z = 0) : float{
		if($x instanceof Vector){
			return $this->maxPlainDistance($x->x, $x->z);
		}
		return max(abs($this->x - $x), abs($this->z - $z));
	}

	public function length() : float{
		return sqrt($this->lengthSquared());
	}

	public function lengthSquared() : float{
		return $this->x * $this->x + $this->y * $this->y + $this->z * $this->z;
	}

	public static function hypot(float $p1, float $p2) : float{
        return sqrt($p1 * $p1 + $p2 * $p2);
    }

	public function normalize() : Vector{
		$len = $this->lengthSquared();
		if($len > 0){
			return $this->divide(sqrt($len));
		}
		$vector = new Vector();
		$vector->zero();
		return $vector;
	}

	public function dot(Vector $v) : float{
		return $this->x * $v->x + $this->y * $v->y + $this->z * $v->z;
	}

	public function cross(Vector $v) : Vector{
		$vector = new Vector();
		$vector->set(
			$this->y * $v->z - $this->z * $v->y,
			$this->z * $v->x - $this->x * $v->z,
			$this->x * $v->y - $this->y * $v->x
		);
		return $vector;
	}

	public function equals(Vector $v) : bool{
		return $this->x == $v->x and $this->y == $v->y and $this->z == $v->z;
	}

	public function getIntermediateWithXValue(Vector $v, float $x) : ?Vector{
		$xDiff = $v->x - $this->x;
		if(($xDiff * $xDiff) < 0.0000001){
			return null;
		}
		$f = ($x - $this->x) / $xDiff;
		if($f < 0 or $f > 1){
			return null;
		}
		$vector = new Vector();
		$vector->set($x, $this->y + ($v->y - $this->y) * $f, $this->z + ($v->z - $this->z) * $f);
		return $vector;
	}

	public function getIntermediateWithYValue(Vector $v, float $y) : ?Vector{
		$yDiff = $v->y - $this->y;
		if(($yDiff * $yDiff) < 0.0000001){
			return null;
		}
		$f = ($y - $this->y) / $yDiff;
		if($f < 0 or $f > 1){
			return null;
		}
		$vector = new Vector();
		$vector->set($this->x + ($v->x - $this->x) * $f, $y, $this->z + ($v->z - $this->z) * $f);
		return $vector;
	}

	public function getIntermediateWithZValue(Vector $v, float $z) : ?Vector{
		$zDiff = $v->z - $this->z;
		if(($zDiff * $zDiff) < 0.0000001){
			return null;
		}
		$f = ($z - $this->z) / $zDiff;
		if($f < 0 or $f > 1){
			return null;
		}else{
			$vector = new Vector();
			$vector->set($this->x + ($v->x - $this->x) * $f, $this->y + ($v->y - $this->y) * $f, $z);
			return $vector;
		}
	}

    public function getDirectionVector(float|int $pitch, float|int $yaw) : Vector{
		$y = -sin(deg2rad($pitch));
		$xz = cos(deg2rad($pitch));
		$x = -$xz * sin(deg2rad($yaw));
		$z = $xz * cos(deg2rad($yaw));
		$vector = new Vector();
		$vector->set($x, $y, $z);
		return $vector->normalize();
	}

    public function getDirectionPlane() : Vector{
		$vector = new Vector();
		$vector->set(-cos(deg2rad($this->location->yaw) - M_PI_2), 0, -sin(deg2rad($this->location->yaw) - M_PI_2));
		return $vector->normalize();
	}

	public function hashCode() : int{
		return $this->x * 31 + $this->y * 31 + $this->z * 31;
	}
}