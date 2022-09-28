<?php

namespace hachkingtohach1\vennv\check\checks\aim;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class AimC extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutPosition) return;   
        
        $this->checkInfo(
            self::ATTACK, "C", "Aim", 3, $origin
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
        $fakeMapViolation->setMaxViolation(8);
        $fakeMapViolation->setMaxTicks(0.45);

        $attackTicks = $profile->getAttackTicks();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $abs = abs($location->getYaw() - $lastLocation->getYaw());
        $abs2 = abs($location->getPitch() - $lastLocation->getPitch());

        if($attackTicks < 2 && $abs > 0 && $abs < 0.01 && $abs2 > 0.2){
            if($fakeMapViolation->handleViolation()){
                $this->handleViolation("Y: ".$abs." P: ".$abs2);
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