<?php

namespace hachkingtohach1\vennv\utils;

class Cuboid{

    private float|int $x1 = 0;
    private float|int $y1 = 0;
    private float|int $z1 = 0;
    private float|int $yaw1 = 0;
    private float|int $pitch1 = 0;
    private float|int $x2 = 0;
    private float|int $y2 = 0;
    private float|int $z2 = 0;
    private float|int $yaw2 = 0;
    private float|int $pitch2 = 0;

    public function set(int|float $x1 = 0, int|float $y1 = 0, int|float $z1 = 0, int|float $yaw1 = 0, int|float $pitch1 = 0, int|float $x2 = 0, int|float $y2 = 0, int|float $z2 = 0, int|float $yaw2 = 0, int|float $pitch2 = 0){
        $this->x1 = $x1;
        $this->y1 = $y1;
        $this->z1 = $z1;
        $this->yaw1 = $yaw1;
        $this->pitch1 = $pitch1;
        $this->x2 = $x2;
        $this->y2 = $y2;
        $this->z2 = $z2;
        $this->yaw2 = $yaw2;
        $this->pitch2 = $pitch2;
    }

	public function getReach() : float{
        return hypot($this->x1 - $this->x2, $this->z1 - $this->z2) - 0.4;
    }

    public function getYawDiff() : int|float{
        return abs(180 - abs($this->yaw1 - $this->yaw2));
    }

    public function getYDiff() : int|float{
        return abs($this->y1 - $this->y2);
    }

    public function getOffset() : int|float{
		$eyeHeight = 2; // Normal is 2, maybe
        $entityLoc = $this->getVector2()->add(0.0, $eyeHeight, 0.0);
        $playerLoc = $this->getVector2()->add(0.0, $eyeHeight, 0.0);
        $vector = new Vector();
        $vector->set($this->yaw1, $this->pitch1, 0.0);
        $playerRotation = $vector;
        $expectedRotation = $this->getRotation2($playerLoc, $entityLoc);
        $deltaYaw =  MathUtil::clamp180($playerRotation->getX() - $expectedRotation->getX());
        $horizontalDistance = $this->getHorizontalDistance2($playerLoc, $entityLoc);
        $distance = $this->getDistance3D2($playerLoc, $entityLoc);
        $offsetX = $deltaYaw * $horizontalDistance * $distance;
        return $offsetX;
    }

    public function getDistance3D() : int|float{
        $toReturn = 0.0;
        $xSqr = ($this->x2 - $this->x1) * ($this->x2 - $this->x1);
        $ySqr = ($this->y2 - $this->y1) * ($this->y2 - $this->y1);
        $zSqr = ($this->z2 - $this->z1) * ($this->z2 - $this->z1);
        $sqrt = sqrt($xSqr + $ySqr + $zSqr);
        $toReturn = abs($sqrt);
        return $toReturn;
    }

	public function getDistance3D2(Vector $one, Vector $two) : int|float{
        $toReturn = 0.0;
        $xSqr = ($two->getX() - $one->getX()) * ($two->getX() - $one->getX());
        $ySqr = ($two->getY() - $one->getY()) * ($two->getY() - $one->getY());
        $zSqr = ($two->getZ() - $one->getZ()) * ($two->getZ() - $one->getZ());
        $sqrt = sqrt($xSqr + $ySqr + $zSqr);
        $toReturn = abs($sqrt);
        return $toReturn;
    }

    public function getRotation() : Vector{
        $dx = $this->x2 - $this->x1;
        $dy = $this->y2 - $this->y1;
        $dz = $this->z2 - $this->z1;
        $distanceXZ = sqrt($dx * $dx + $dz * $dz);
        $yaw = (float) (atan2($dz, $dx) * 180.0 / 3.141592653589793) - 90.0;
        $pitch = (float) -(atan2($dy, $distanceXZ) * 180.0 / 3.141592653589793);
        $vector = new Vector();
        $vector->set($yaw, $pitch, 0.0);
        return $vector;
    }

	public function getRotation2(Vector $one, Vector $two) : Vector{
        $dx = $two->getX() - $one->getX();
        $dy = $two->getY() - $one->getY();
        $dz = $two->getZ() - $one->getZ();
        $distanceXZ = sqrt($dx * $dx + $dz * $dz);
        $yaw = (float) (atan2($dz, $dx) * 180.0 / 3.141592653589793) - 90.0;
        $pitch = (float) -(atan2($dy, $distanceXZ) * 180.0 / 3.141592653589793);
        $vector = new Vector();
        $vector->set($yaw, $pitch, 0.0);
        return $vector;
    }

    private function getHorizontalVector(Vector $vector) : Vector{
        $vector->setY(0);
        return $vector;
    }

    public function getHorizontalDistance() : int|float{
        $x = abs(abs($this->x1) - abs($this->x2));
        $z = abs(abs($this->z1) - abs($this->z2));
        return sqrt($x * $x + $z * $z);
    }

	public function getHorizontalDistance2(Vector $one, Vector $two) : int|float{
        $x = abs(abs($one->getX()) - abs($two->getX()));
        $z = abs(abs($one->getZ()) - abs($two->getZ()));
        return sqrt($x * $x + $z * $z);
    }

    public function getOffset2() : int|float{
        return $this->getVector1()->subtract($this->x2, $this->y2, $this->z2)->length();
    }

    public function getAirSpeed() : int|float{
        return $this->getOffset($this->getHorizontalVector($this->getVector1()), $this->getHorizontalVector($this->getVector2()));
    }

	public function getVector1() : Vector{
        $vector = new Vector();
        $vector->set($this->x1, $this->y1, $this->z1);
		return $vector;
	}

	public function getVector2() : Vector{
        $vector = new Vector();
        $vector->set($this->x2, $this->y2, $this->z2);
		return $vector;
    }

    public function getOffset3(Vector $one, Vector $two) : int|float{
        $x = $one->getX() - $two->getX();
        $y = $one->getY() - $two->getY();
        $z = $one->getZ() - $two->getZ();
        return sqrt($x * $x + $y * $y + $z * $z);
    }

    public function setLength(Vector $vector, float $length) : Vector{
        $vector->normalize();
        $vector->multiply($length);
        return $vector;
    }

    public function withLimit(Vector $vector, float $limit) : Vector{
        $length = $vector->length();
        if($length > $limit){
            $vector->multiply($limit / $length);
        }
        return $vector;
    }

    public function hashCode() : int{
        $hash = 7;
        $hash = 31 * $hash + $this->x1;
        $hash = 31 * $hash + $this->y1;
        $hash = 31 * $hash + $this->z1;
        $hash = 31 * $hash + $this->x2;
        $hash = 31 * $hash + $this->y2;
        $hash = 31 * $hash + $this->z2;
        return $hash;
    }
}