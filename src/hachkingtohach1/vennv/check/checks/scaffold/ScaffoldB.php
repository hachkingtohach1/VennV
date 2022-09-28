<?php

namespace hachkingtohach1\vennv\check\checks\scaffold;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockPlace;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\SampleList;

class ScaffoldB extends PacketCheck{

    private static array $sampleList = [];
    private static array $lastTime = [];
    private static array $lastDelta = [];

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayInBlockPlace) return;
        
        $this->checkInfo(
            self::INTERACT, "B", "Scaffold", 1, $origin
        );

        $profile = $this->getProfile();

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        if(!$this->isHaveSampleList($profile->getName())){
            $this->setSampleList($profile->getName());
        }

        $sampleList = $this->getSampleList($profile->getName());
        $sampleList->setMaxSample(10);

        $joinTicks = $profile->getJoinTicks();

        $time = microtime(true);

        if(!$this->isHaveLastTime($profile->getName())){
            $this->setLastTime($profile->getName(), $time);
        }

        if(!$this->isHaveLastDelta($profile->getName())){
            $this->setLastDelta($profile->getName(), 0);
        }

        $lastTime = $this->getLastTime($profile->getName());

        $this->setLastTime($profile->getName(), $time);

        $delta = $time - $lastTime;

        $lastDelta = $this->getLastDelta($profile->getName());

        $this->setLastDelta($profile->getName(), $delta);

        if($joinTicks > 2 && $delta > 0.05 && $delta != $lastDelta){
            $result = $sampleList->handleSample($delta);
            if(count($result) >= $sampleList->getMaxSample()){
                $deviation = $this->getDeviation($result);
                $average = $this->getAverageLong($result);
                if($deviation > 0 && $deviation < 0.074388829874346 && $average < 0.65){
                    $this->handleViolation("D: ".$deviation." A: ".$average);
                }
            }
        }
    }

    private function getDeviation(array $array) : int|float{
        if(empty($array)){
            return 0;
        }
        return sqrt($this->getVariance($array) / (count($array) - 1));
    }

    private function getAverageLong(array $array) : int|float{
        if(empty($array)){
            return 0;
        }
        return $this->getSumLong($array) / count($array);
    }

    private function getSumLong(array $array) : int|float{
        if(empty($array)){
            return 0;
        }
        $sum = 0;
        foreach($array as $data){
            $sum += $data;
        }
        return $sum;
    }

    private function getVariance(array $array) : int|float{
        if(empty($array)){
            return 0;
        }
        $count = 0;
        $sum = 0;
        $variance = 0;
        $average = 0;
        foreach($array as $data){
            $sum += $data;
            ++$count;
        }
        $average = $sum / $count;
        foreach($array as $data){
            $variance += pow($data - $average, 2);
        }
        return $variance;
    }

    private function isHaveLastTime(string $profileName) : bool{
        return isset(self::$lastTime[$profileName]);
    }

    private function setLastTime(string $profileName, int|float $data) : void{
        self::$lastTime[$profileName] = $data;
    }

    private function getLastTime(string $profileName) : int|float{
        return self::$lastTime[$profileName];
    }

    private function isHaveLastDelta(string $profileName) : bool{
        return !empty(self::$lastDelta[$profileName]);
    }

    private function setLastDelta(string $profileName, int|float $data) : void{
        self::$lastDelta[$profileName] = $data;
    }

    private function getLastDelta(string $profileName) : int|float{
        return self::$lastDelta[$profileName];
    }

    private function isHaveSampleList(string $profileName) :bool{
        return !empty(self::$sampleList[$profileName]);
    }

    private function getSampleList(string $profileName) : SampleList{
        return self::$sampleList[$profileName];
    }

    private function setSampleList(string $profileName) : void{
        self::$sampleList[$profileName] = new SampleList();
    }
}