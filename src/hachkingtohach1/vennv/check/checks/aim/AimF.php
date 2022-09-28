<?php

namespace hachkingtohach1\vennv\check\checks\aim;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class AimF extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutPosition) return;   
        
        $this->checkInfo(
            self::ATTACK, "F", "Aim", 3, $origin
        );

        $profile = $this->getProfile();

        $teleportTicks = $profile->getTeleportTicks();

        if($teleportTicks < 3){
            return;
        }

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(7);
        $fakeMapViolation->setMaxTicks(0.5);

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $deltaYaw = abs($location->getYaw() - $lastLocation->getYaw());
        $deltaPitch = abs($location->getPitch() - $lastLocation->getPitch());

        if($deltaYaw > 1 && $deltaYaw < 5 && $deltaPitch === 0.0 && abs($location->getPitch()) < 85){
            if($fakeMapViolation->handleViolation()){
                $this->handleViolation("Y: ".$deltaYaw." P: ".$deltaPitch);
            }
        }else{
            $fakeMapViolation->addViolation(-0.5);
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