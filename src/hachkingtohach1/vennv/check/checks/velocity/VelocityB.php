<?php

namespace hachkingtohach1\vennv\check\checks\velocity;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutUnderAttack;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class VelocityB extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutUnderAttack) return;
        
        $this->checkInfo(
            self::MOVE, "B", "Velocity", 1, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(3);
        $fakeMapViolation->setMaxTicks(0.5);

        $onLiquid = $profile->isOnLiquid();
        $onWeb = $profile->isOnWeb();

        if($onLiquid || $onWeb){
            return;
        }

        $teleportTicks = $profile->getTeleportTicks();

        if($teleportTicks < 5){
            return;
        }
        
        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $deltaY = $profile->getDeltaY();
        $velocityY = $profile->getVelocityY();

        if(fmod($lastLocation->getY(), 1) == 0 && fmod($location->getY(), 1) > 0 && $deltaY > 0 && $deltaY < 0.41999998688697815 && !$location->isOnGround()){
            if($velocityY < 0){
                $raito = $deltaY / $velocityY;
                if($raito < 0.99){
                    if($fakeMapViolation->handleViolation()){                   
                        $this->handleViolation("R: ".$raito, 1);
                    }                 
                }
            }
        }else{
            $this->addViolation(-0.05);
        }
    }

    private function getFakeMapViolation(string $profileName) : FakeMapViolation{
        return self::$fakeMapViolation[$profileName];
    }

    private function setFakeMapViolation(string $profileName) : void{
        self::$fakeMapViolation[$profileName] = new FakeMapViolation();
    }

    private function isHaveFakeMapViolation(string $profileName) :bool{
        return !empty(self::$fakeMapViolation[$profileName]);
    }

    public function unsetFakeMapViolation(string $profileName) : void{
        if(isset(self::$fakeMapViolation[$profileName])){
            unset(self::$fakeMapViolation[$profileName]);
        }
    }
}