<?php

namespace hachkingtohach1\vennv\check\checks\fly;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityEffect;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class FlyH extends PacketCheck{

    private static array $fakeMapViolation = [];
    private static array $jump = [];

    public function getCloning() : int{
        return 2;
    }

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayInFlying) return;

        if($packet->isAllowed) return;
        
        $this->checkInfo(
            self::FLY, "H", "Fly", 5, $origin
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

        $onLiquid = $profile->isOnLiquid();

        $inVehicle = $profile->getInVehicle();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $joinTicks = $profile->getJoinTicks();
        $deathTicks = $profile->getDeathTicks();
        $respawnTicks = $profile->getRespawnTicks();
        $teleportTicks = $profile->getTeleportTicks();

        if($joinTicks < 3 || $deathTicks < 3 || $respawnTicks < 3 || $teleportTicks < 3){
            return;
        }

        $effects = $profile->getEffectHandler();

        $jumpLevel = 0;
        foreach($effects->getEffects() as $effect => $data){
            if($effect === VPacketPlayOutEntityEffect::JUMP){
                $jumpLevel = $data["amplifier"];
            }
        }

        if(!isset(self::$jump[$profile->getName()])){
            self::$jump[$profile->getName()] = 0;
        }

        if(
            (($deathTicks > $respawnTicks) || ($deathTicks > 3 && $respawnTicks > 3)) &&
            $lastLocation->isOnGround() &&
            $lastLocation->getY() > $location->getY() && 
            !$inVehicle && !$onLiquid && $jumpLevel <= 0 && 
            isset(self::$jump[$profile->getName()])
        ){
            $jump = self::$jump[$profile->getName()];
            $d1 = $lastLocation->getY() - max(0, $location->getY());
            if($d1 > 100000){
                $this->handleViolation("D1: ".$d1, $d1);
            }
            $d2 = 0.41999998688699;
            $d3 = max(0.5, $d2 + max($jump, $jumpLevel) * 0.2);
            $d4 = $d1 - $d3;
            if($location->isOnGround()){
                self::$jump[$profile->getName()] = $jumpLevel;
            }
            if($lastLocation->isOnGround() && $location->isOnGround() && ($d4 == 0.0625 || $d4 == 0.10000002384185791)){
                return;
            }
            $class = new class extends PacketCheck{
                public function handle(VPacket $packet, string $origin) : void{
                    $this->checkInfo(
                        self::FLY, "2H", "Fly", 5, $origin
                    );
                }
            };
            $class->handle($packet, $origin);
            if($d1 > $d3 && abs($d1 - 0.5) > 1.0E-12){
                $class->handleViolation("D1: ".$d1." D3: ".$d3." D4: ".$d4);
            }else{
                $class->addViolation(-0.05);
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