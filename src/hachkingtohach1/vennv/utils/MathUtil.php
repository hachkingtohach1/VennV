<?php

namespace hachkingtohach1\vennv\utils;

use hachkingtohach1\vennv\utils\custom\Pair;

class MathUtil{

    public const Y_MAX = 256;
    public const Y_MIN = 0;

    public const EXPANDER = 1.6777216E7;
    public const MINIMUM_ROTATION_DIVISOR = 131072;

    private static $SIN_TABLE = [];
    private static $SIN_TABLE_FAST = [];
    private const FAST_MATH = false;

    public static function sin(float $val) : float{
        return self::FAST_MATH ? self::$SIN_TABLE_FAST[($val * 651.8986) & 4095] : self::$SIN_TABLE[($val * 10430.378) & 65535];
    }

    public static function cos(float $val) : float{
        return self::FAST_MATH ? self::$SIN_TABLE_FAST[($val + (M_PI / 2) * 651.8986) & 4095] : self::$SIN_TABLE[($val * 10430.378 + 16384.0) & 65535];
    }

    public static function getDeviation(array $nums) : float{
        if(count($nums) < 1){
            return 0.0;
        }
        $variance = 0;
        $average = array_sum($nums) / count($nums);
        foreach($nums as $num){
            $variance += pow($num - $average, 2);
        }
        return sqrt($variance / count($nums));
    }

    public static function getAverage(array $nums) : float{
        if(count($nums) === 0){
            return 0.0;
        }
        return array_sum($nums) / count($nums);
    }

    public static function vectorAngle(Vector $a, Vector $b) : float{
        try{
            $dot = $a->dot($b) / ($a->length() * $b->length());
            return acos($dot);
        } catch (\ErrorException $e){
            return -1;
        }
    }

    public static function getKurtosis(array $data) : float{
        try{
            $sum = array_sum($data);
            $count = count($data);
            if($count < 3){
                return 0;
            }
            $efficiencyFirst = $count * ($count + 1) / (($count - 1) * ($count - 2) * ($count - 3));
            $efficiencySecond = 3 * pow($count - 1, 2) / (($count - 2) * ($count - 3));
            $average = $sum / $count;
            $variance = 0.0;
            $varianceSquared = 0.0;
            foreach($data as $number){
                $variance += pow($average - $number, 2);
                $varianceSquared += pow($average - $number, 4);
            }
            if($variance === 0.0){
                return 0.0;
            }
            return $efficiencyFirst * ($varianceSquared / pow($variance / $sum, 2)) - $efficiencySecond;
        } catch(\ErrorException $e){
            return 0.0;
        }
    }

    public static function getSkewness(array $data) : float{
        try{
            $sum = array_sum($data);
            $count = count($data);

            $numbers = $data;
            sort($numbers);

            $mean = $sum / $count;
            $median = ($count % 2 !== 0) ? $numbers[$count / 2] : ($numbers[($count - 1) / 2] + $numbers[$count / 2]) / 2;
            $variance = self::getVariance($data);
            return $variance > 0 ? 3 * ($mean - $median) / $variance : 0;
        } catch(\ErrorException $e){
            return 0.0;
        }
    }

    public static function getVariance(array $data) : float{
        $variance = 0;
        $mean = array_sum($data) / count($data);
        foreach ($data as $number) {
            $variance += pow($number - $mean, 2);
        }
        return $variance / count($data);
    }

    public static function getOutliers(array $collection) : Pair{
        $q1 = self::getMedian(array_splice($collection, 0, (int) ceil(count($collection) / 2)));
        $q3 = self::getMedian(array_splice($collection, (int) ceil(count($collection) / 2), count($collection)));
        $iqr = abs($q1 - $q3);
        $lowThreshold = $q1 - 1.5 * $iqr;
        $highThreshold = $q3 + 1.5 * $iqr;
        $x = [];
        $y = [];
        foreach($collection as $value) {
            if ($value < $lowThreshold) {
                $x[] = $value;
            } elseif ($value > $highThreshold) {
                $y[] = $value;
            }
        }
        $pair = new Pair();
        $pair->set($x, $y);
        return $pair;
    }

    public static function getMedian(array $data) : float{
        if (count($data) % 2 === 0) {
            return ($data[count($data) / 2] + $data[count($data) / 2 - 1]) / 2;
        } else {
            return $data[count($data) / 2];
        }
    }

    public static function millisToTicks(int|float $millis) : int|float{
        return (int)$millis / 50;
    }

    public static function ticksToMillis(int|float $ticks) : int|float{
        return $ticks * 50;
    }

    public static function nanosToMillis(int|float $nanoseconds) : int|float{
        return ($nanoseconds / 1000000);
    }

    public static function strictClamp360(int|float $value) : int|float{
        if($value > 360.0){
            $value -= 360.0;
        }
        if($value < 0.0){
            $value += 360.0;
        }
        return $value;
    }

    public static function clamp180(int|float $theta) : int|float{
        $theta %= 360.0;
        if($theta >= 180.0){
            $theta -= 360.0;
        }
        if($theta < -180.0){
            $theta += 360.0;
        }
        return $theta;
    }

    public static function getDistanceBetweenAngles360(int|float $n, int|float $n2) : int|float{
        $abs = abs(fmod($n, 360.0) - fmod($n2, 360.0));
        return abs(min(360.0 - $abs, $abs));
    }

    public static function getDistanceBetweenAngles360b(int|float $n, int|float $n2) : int|float{
        $distance = abs($n - $n2) % 360.0;
        return $distance > 180.0 ? 360.0 - $distance : $distance;
    }

    public static function onGround(int|float $n) : bool{
        return fmod($n, 0.015625) == 0;
    }

    public static function getAbsoluteGcd(int|float $current, int|float $last) : int|float{
        $currentExpanded = ($current * self::EXPANDER);
        $lastExpanded = ($last * self::EXPANDER);
        return self::gcd($currentExpanded, $lastExpanded);
    }

    private static function gcd(int|float $current, int|float $last) : int|float{
        return ($last <= 16384) ? $current : self::gcd($last, fmod($current, $last));
    }

    public static function gcd2(float $a, float $b) : float{
        if($a < $b){
            return self::gcd2($b, $a);
        }
        if(abs($b) < 0.001){
            return $a;
        } else {
            return self::gcd2($b, $a - floor($a / $b) * $b);
        }
    }

    public static function getArrayGCD(array $nums) : float{
        if(count($nums) < 2){
            return 0.0;
        }
        $result = $nums[0];
        for($i = 1; $i < count($nums); $i++){
            $result = self::gcd($nums[$i], $result);
        }
        return $result;
    }

    public static function getArrayGCD2(array $nums) : float{
        if(count($nums) < 2){
            return 0.0;
        }
        $result = $nums[0];
        for($i = 1; $i < count($nums); $i++){
            $result = self::gcd2($nums[$i], $result);
        }
        return $result;
    }

    public static function getAbsoluteDelta(int|float $one, int|float $two) : int|float{
        return abs(abs($one) - abs($two));
    }

    public static function pingFormula(int|float $ping) : int{
        return (int)ceil($ping / 50);
    }

    public static function calculateMinAndMaxValues(Vector $pos1, Vector $pos2, bool $clampY, ?int &$minX, ?int &$maxX, ?int &$minY, ?int &$maxY, ?int &$minZ, ?int &$maxZ) : void{
		$minX = (int)min($pos1->getX(), $pos2->getX());
		$maxX = (int)max($pos1->getX(), $pos2->getX());
		$minY = (int)min($pos1->getY(), $pos2->getY());
		$maxY = (int)max($pos1->getY(), $pos2->getY());
		$minZ = (int)min($pos1->getZ(), $pos2->getZ());
		$maxZ = (int)max($pos1->getZ(), $pos2->getZ());
		if(!$clampY) {
			return;
		}
		$minY = min(self::Y_MAX - 1, max(self::Y_MIN, $minY));
		$maxY = min(self::Y_MAX - 1, max(self::Y_MIN, $maxY));
	}

    public static function getRotationFormTwoVector(Vector $pos1, Vector $pos2) : array{
        $xDiff = $pos2->getX() - $pos1->getX();
        $yDiff = $pos2->getY() - $pos1->getY() + 0.2;
        $zDiff = $pos2->getZ() - $pos1->getZ();
        $dist = sqrt($xDiff * $xDiff + $zDiff * $zDiff);
        $yaw =  (atan2($zDiff, $xDiff) * 180 / M_PI) - 45;
        $pitch = -(atan2($yDiff, $dist) * 180 / M_PI);
        return ["yaw" => $yaw, "pitch" => $pitch];
    }

    public static function distance(Vector $from, Vector $to){
        return sqrt(pow($from->getX() - $to->getX(), 2) + pow($from->getY() - $to->getY(), 2) + pow($from->getZ() - $to->getZ(), 2));
    }

    public static function getLuckyAura(Location $location, Vector $vector) : int|float{
        return tan(deg2rad($location->getPitch())) * $location->distanceXZ($vector) - $location->getY() + $vector->getY();
    }

    //create function lowestAbs
    public static function lowestAbs(int|float $a, int|float $b) : int|float{
        return abs($a) < abs($b) ? $a : $b;
    }

    public static function init() : void{
        for($i = 0; $i < 65536; $i++){
            self::$SIN_TABLE[$i] = sin($i * M_PI * 2 / 65536);
        }
        for($i = 0; $i < 4096; $i++){
            self::$SIN_TABLE_FAST[$i] = sin(($i + 0.5) / 4096 * (M_PI * 2));
        }
        for($i = 0; $i < 360; $i += 90){
            self::$SIN_TABLE_FAST[($i * 11.377778) & 4095] = sin($i * 0.017453292);
        }
    }
}