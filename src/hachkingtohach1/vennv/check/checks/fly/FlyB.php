<?php

namespace hachkingtohach1\vennv\check\checks\fly;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class FlyB extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayInFlying) return;

        if($packet->isAllowed) return;
        
        $this->checkInfo(
            self::FLY, "B", "Fly", 3, $origin
        );

        $profile = $this->getProfile();

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(5);
        $fakeMapViolation->setMaxTicks(0.5);

        $underAttackTicks = $profile->getUnderAttackTicks();

        $pingTicks = $profile->getPingTicks();
        $maxPingTicks = $profile->getMaxPingTicks();

        $onGround = $profile->getOnGround();
        $onLiquid = $profile->isOnLiquid();

        $inVehicle = $profile->getInVehicle();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $offsetH = hypot($lastLocation->getX() - $location->getX(), $lastLocation->getZ() - $location->getZ());
        $offsetY = $lastLocation->getY() - $location->getY();

        if(!$inVehicle && !$onGround && !$onLiquid && $underAttackTicks > 1.5 && $pingTicks < 2 && $maxPingTicks > 1){
            if($offsetH > 0.0 && $offsetY == 0.0){
                if($fakeMapViolation->handleViolation()){
                    $this->handleViolation("O: ".$offsetH);
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