<?php

namespace hachkingtohach1\vennv\check\checks\jesus;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class JesusB extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{
        
        $this->checkInfo(
            self::MOVE, "B", "Jesus", 1, $origin
        );

        $profile = $this->getProfile();

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        if(!$profile->isOnLiquid()) return;

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(90);
        $fakeMapViolation->setMaxTicks(0.25);

        if($packet instanceof VPacketPlayInFlying){
            if($packet->isAllowed){
                $fakeMapViolation->addViolation(-0.1);
            }
        }

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();
        $lastLastLocation = $profile->getLastLastLocation();

        $deltaY = $profile->getDeltaY();

        $teleportTicks = $profile->getTeleportTicks();

        if($deltaY != 0 && !$location->isOnGround() && !$lastLocation->isOnGround() && !$lastLastLocation->isOnGround()){
            if($teleportTicks > 2 && $deltaY > -0.01 && $deltaY < 0.069){
                if($fakeMapViolation->handleViolation()){
                    $this->handleViolation("DY: ".$deltaY);
                }else{
                    $fakeMapViolation->addViolation(-0.1);
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
        return !empty(self::$fakeMapViolation[$profileName]);
    }
}