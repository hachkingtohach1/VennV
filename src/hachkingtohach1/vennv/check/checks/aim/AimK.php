<?php

namespace hachkingtohach1\vennv\check\checks\aim;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class AimK extends PacketCheck{

    private static array $lastYaw = [];
    private static array $lastPitch = [];
    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutPosition) return; 
        
        $this->checkInfo(
            self::ATTACK, "K", "Aim", 10, $origin
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
        $fakeMapViolation->setMaxViolation(4);
        $fakeMapViolation->setMaxTicks(0.5);

        $location = $profile->getLocation();

        if(
            isset(self::$lastYaw[$profile->getName()]) &&
            isset(self::$lastPitch[$profile->getName()])
        ){
            $lastYaw = self::$lastYaw[$profile->getName()];
            $pitch = abs($location->getYaw() - $lastYaw) % 180;
            $lastPitch = self::$lastPitch[$profile->getName()];

            if($pitch > 0.1 && round($pitch) == $pitch){
                if($pitch == $lastPitch){
                    if($fakeMapViolation->handleViolation()){
                        $this->handleViolation("P: ".$pitch." LY: ".$lastYaw);
                    }
                }else{
                    $this->addViolation(-0.001);
                }
                self::$lastPitch[$profile->getName()] = round($pitch);
            }else{
                self::$lastPitch[$profile->getName()] = 0;
            }
            self::$lastYaw[$profile->getName()] = $location->getYaw();      
        }else{
            self::$lastYaw[$profile->getName()] = $location->getYaw();
            self::$lastPitch[$profile->getName()] = $location->getPitch();
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