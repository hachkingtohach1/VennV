<?php

namespace hachkingtohach1\vennv\check\checks\fly;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityEffect;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class FlyF extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayInFlying) return;

        if($packet->isAllowed) return;
        
        $this->checkInfo(
            self::FLY, "F", "Fly", 5, $origin
        );

        $profile = $this->getProfile();

        if($profile->getPlacingBlock()){
            $profile->setPlacingBlock(false);
            return;
        }

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(4);
        $fakeMapViolation->setMaxTicks(0.5);

        $joinTicks = $profile->getJoinTicks();
        $deathTicks = $profile->getDeathTicks();
        $respawnTicks = $profile->getRespawnTicks();
        $underAttackTicks = $profile->getUnderAttackTicks();

        if($underAttackTicks < 3){
            return;
        }

        $onGround = $profile->getOnGround();
        $onLiquid = $profile->isOnLiquid();

        $inVehicle = $profile->getInVehicle();

        $effects = $profile->getEffectHandler();

        $add = 1;
        foreach($effects->getEffects() as $effect => $data){
            if($effect === VPacketPlayOutEntityEffect::JUMP){
                $add -= $data["amplifier"] * 0.1;
            }
        }

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();
        $lastLastLocation = $profile->getLastLastLocation();

        if((($deathTicks > $respawnTicks) || ($deathTicks > 3 && $respawnTicks > 3)) && !$packet->onGround && !$inVehicle && !$onLiquid && !$onGround && !$location->isOnGround() && !$lastLocation->isOnGround() && !$lastLastLocation->isOnGround() && $joinTicks > 2){
            if(
                $location->getY() > $lastLocation->getY() &&
                $lastLocation->getY() > $lastLastLocation->getY()
            ){
                if($fakeMapViolation->handleViolation($add)){
                    $this->handleViolation("Y: ".$location->getY());
                }
            }else{
                $fakeMapViolation->addViolation(-0.05);
            }
        }else{
            $fakeMapViolation->setViolation(0);
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