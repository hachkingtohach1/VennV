<?php

namespace hachkingtohach1\vennv\check\checks\killaura;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInArmAnimation;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInUseEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class KillAuraA extends PacketCheck{

    private static array $received = [];
    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{
        
        $this->checkInfo(
            self::ATTACK, "A", "KillAura", 5, $origin
        );

        $profile = $this->getProfile();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();
        $lastLastLocation = $profile->getLastLastLocation();

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
        $fakeMapViolation->setMaxTicks(0.47);

        if($packet instanceof VPacketPlayInFlying){
            if($packet->isAllowed){
                $fakeMapViolation->addViolation(-0.1);
            }
        }

        if(!isset(self::$received[$profile->getName()])){
            self::$received[$profile->getName()] = false;
        }

        if($packet instanceof VPacketPlayInArmAnimation){
            self::$received[$profile->getName()] = true;
        }

        $onLiquid = $profile->isOnLiquid();
        $inVehicle = $profile->getInVehicle();

        if($packet instanceof VPacketPlayOutPosition){
            if(
                !$packet->onGround && !$onLiquid && !$inVehicle &&
                (int)$location->getX() != (int)$lastLocation->getX() || 
                (int)$location->getZ() != (int)$lastLocation->getZ() ||
                (int)$location->getX() != (int)$lastLastLocation->getX() ||
                (int)$location->getZ() != (int)$lastLastLocation->getZ() ||
                (int)$lastLocation->getX() != (int)$lastLastLocation->getX() ||
                (int)$lastLocation->getZ() != (int)$lastLastLocation->getZ()
            ){
                self::$received[$profile->getName()] = false;
            }
        }

        if($packet instanceof VPacketPlayInUseEntity){
            if($packet->action === VPacketPlayInUseEntity::ACTION_ATTACK){
                if(!self::$received[$profile->getName()]){
                    if($fakeMapViolation->handleViolation()){
                        $this->handleViolation("M: ".microtime(true));
                    }
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