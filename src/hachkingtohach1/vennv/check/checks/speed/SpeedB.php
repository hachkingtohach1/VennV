<?php

namespace hachkingtohach1\vennv\check\checks\speed;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;
use hachkingtohach1\vennv\utils\MathUtil;
use hachkingtohach1\vennv\utils\php\DoubleWrapper;

class SpeedB extends PacketCheck{

    private static array $lastAngleDiff = [];
    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutPosition) return;
        
        $this->checkInfo(
            self::MOVE, "B", "Speed", 1, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(5);
        $fakeMapViolation->setMaxTicks(1.311141);

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $deltaXZ = $profile->getDeltaXZ();

        $moveTicks = $profile->getMoveTicks();
        $velocityTicks = $profile->getVelocityTicks();
        $teleportTicks = $profile->getTeleportTicks();

        if($teleportTicks < 2 || $moveTicks > $velocityTicks){
            return;
        }

        $speed = $profile->getSpeed();
        $lastSpeed = $speed/0.2;

        $distanceBetweenAngles360 = MathUtil::getDistanceBetweenAngles360(rad2deg(-atan2($lastLocation->getX() - $location->getX(), $lastLocation->getZ() - $location->getZ())), $lastLocation->getYaw());
        $doubleWrapper = new DoubleWrapper();
        $doubleWrapper->set($doubleWrapper->doubleWrapper(0.0437));
        $doubleWrapper->addAndGet(max($speed * 20, 0));

        if($speed > 0.2){
            $doubleWrapper->addAndGet($doubleWrapper->get() * $lastSpeed);
        }

        if($this->getLastAngleDiff($profile->getName()) !== null && $packet->onGround){
            if($this->getLastAngleDiff($profile->getName()) > 0.5){
                $doubleWrapper->addAndGet(0.03);
            }
            if($deltaXZ > $doubleWrapper->get()){
                if($fakeMapViolation->handleViolation()){
                    $this->handleViolation(" DXZ :".$deltaXZ." DWR: ".$doubleWrapper->get()." LAD: ".$this->getLastAngleDiff($profile->getName()));
                }
            }
        }
        $this->setLastAngleDiff($profile->getName(), $distanceBetweenAngles360);

        $fakeMapViolation->debugTicks();
    }

    private function getLastAngleDiff(string $profileName) : int|float|null{
        if(isset(self::$lastAngleDiff[$profileName])){
            return self::$lastAngleDiff[$profileName];
        }
        return null;
    }

    private function getFakeMapViolation(string $profileName) : FakeMapViolation{
        return self::$fakeMapViolation[$profileName];
    }

    private function setLastAngleDiff(string $profileName, int|float $angleDiff) : void{
        self::$lastAngleDiff[$profileName] = $angleDiff;
    }

    private function setFakeMapViolation(string $profileName) : void{
        self::$fakeMapViolation[$profileName] = new FakeMapViolation();
    }

    private function isHaveFakeMapViolation(string $profileName) :bool{
        return isset(self::$fakeMapViolation[$profileName]);
    }
}