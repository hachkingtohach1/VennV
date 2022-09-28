<?php

namespace hachkingtohach1\vennv\check\checks\speed;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class SpeedD extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayOutPosition) return;
        
        $this->checkInfo(
            self::MOVE, "D", "Speed", 5, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(7);
        $fakeMapViolation->setMaxTicks(0.5);

        $onGround = $profile->getOnGround();

        $speed = $profile->getSpeed();

        $onLiquid = $profile->isOnLiquid();
        $onIce = $profile->isOnIce();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();
        $lastLastLocation = $profile->getLastLastLocation();

        $deltaX = $profile->getDeltaX();
        $lastDeltaX = $profile->getLastDeltaX();

        $teleportTicks = $profile->getTeleportTicks();

        if($deltaX != 0 && $lastDeltaX != 0 && $onGround && !$onLiquid && $location->isOnGround() && $lastLocation->isOnGround() && $lastLastLocation->isOnGround()){
            if($teleportTicks > 2){
                $limit = $speed * 25;
                $limit += $onIce ? $limit * 2 : 0;
                $distX = $location->getX() - $lastLocation->getX();
                $distZ = $location->getZ() - $lastLocation->getZ();
                $dist = ($distX * $distX) + ($distZ * $distZ);
                $lastDist = $dist;
                $shiftedLastDist = $lastDist * 0.91;
                $equalness = $dist - $shiftedLastDist;
                $scaledEqualness = $equalness * 138;  
                if($scaledEqualness > $limit){
                    $fvl = $fakeMapViolation->getViolations() + 1;
                    if($fakeMapViolation->handleViolation()){
                        $this->handleViolation("S: ".$scaledEqualness." F: ".$fvl);
                    }
                }else{
                    $this->addViolation(-0.1);
                }
            }
        }
    }

    private function getFakeMapViolation(string $profileName) : FakeMapViolation{
        return self::$fakeMapViolation[$profileName];
    }

    private function setFakeMapViolation(string $profileName) : void{
        self::$fakeMapViolation[$profileName] = new FakeMapViolation();
    }

    private function isHaveFakeMapViolation(string $profileName) :bool{
        return isset(self::$fakeMapViolation[$profileName]);
    }
}