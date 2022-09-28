<?php

namespace hachkingtohach1\vennv\check\checks\speed;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;
use hachkingtohach1\vennv\utils\MathUtil;

class SpeedC extends PacketCheck{

    private static array $lastAngle = [];
    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayOutPosition) return;
        
        $this->checkInfo(
            self::MOVE, "C", "Speed", 1, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(10);
        $fakeMapViolation->setMaxTicks(0.76);

        $speed = $profile->getSpeed();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $teleportTicks = $profile->getTeleportTicks();

        if($speed > 0.1 && $location->isOnGround() && $lastLocation->isOnGround()){
            if($teleportTicks > 2){
                $x = $lastLocation->getX() - $location->getX();
                $z = $lastLocation->getZ() - $location->getZ();
                $degrees = rad2deg(-atan2($x, $z));
                $distance = min(MathUtil::getDistanceBetweenAngles360($degrees, $lastLocation->getYaw()), MathUtil::getDistanceBetweenAngles360($degrees, $location->getYaw()));
                if($this->getLastAngle($profile->getName()) != null && $x != 0 && $z != 0){
                    $distance2 = MathUtil::getDistanceBetweenAngles360($this->getLastAngle($profile->getName()), $distance);
                    if($distance > 50){
                        if($distance2 < 5){
                            if($fakeMapViolation->handleViolation()){
                                //$this->handleViolation("D1: ".$distance." D2: ".$distance2);
                            }
                        }
                    }
                }
                $this->setLastAngle($profile->getName(), $distance);
            }
        }
    }

    private function getLastAngle(string $profileName) : int|float|null{
        if(isset(self::$lastAngle[$profileName])){
            return self::$lastAngle[$profileName];
        }
        return null;
    }

    private function getFakeMapViolation(string $profileName) : FakeMapViolation{
        return self::$fakeMapViolation[$profileName];
    }

    private function setLastAngle(string $profileName, int|float $angleDiff) : void{
        self::$lastAngle[$profileName] = $angleDiff;
    }

    private function setFakeMapViolation(string $profileName) : void{
        self::$fakeMapViolation[$profileName] = new FakeMapViolation();
    }

    private function isHaveFakeMapViolation(string $profileName) :bool{
        return isset(self::$fakeMapViolation[$profileName]);
    }
}