<?php

namespace hachkingtohach1\vennv\check\checks\aim;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInUseEntity;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class AimH extends PacketCheck{

    private static array $lastYaw = [];
    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayInUseEntity) return; 
        
        $this->checkInfo(
            self::ATTACK, "H", "Aim", 7, $origin
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

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        if(
            $location->getYaw() == $lastLocation->getYaw() ||
            !$location->isOnGround()
        ) return;

        $deltaYaw = abs($location->getYaw() - $lastLocation->getYaw()) % 180;

        if(isset(self::$lastYaw[$profile->getName()])){
            $lastYaw = self::$lastYaw[$profile->getName()];
            if($deltaYaw > 1 && $lastYaw > 1 && $deltaYaw == $lastYaw){
                $fakeMapViolation->addViolation(1);
            }else{
                if($fakeMapViolation->getViolations() > 0){
                    $fakeMapViolation->addViolation(-1);
                }
                $this->addViolation(-0.01);
            }
            if($fakeMapViolation->getViolations() > 1){
                $this->handleViolation("D: ".$deltaYaw." LY: ".$lastYaw);
            }
        }

        self::$lastYaw[$profile->getName()] = $deltaYaw;
    }

    public function setFakeMapViolation(string $profileName) : void{
        self::$fakeMapViolation[$profileName] = new FakeMapViolation();
    }

    public function getFakeMapViolation(string $profileName) : FakeMapViolation{
        return self::$fakeMapViolation[$profileName];
    }

    public function isHaveFakeMapViolation(string $profileName) : bool{
        return isset(self::$fakeMapViolation[$profileName]);
    }
}