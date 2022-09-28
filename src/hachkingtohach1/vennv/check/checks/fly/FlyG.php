<?php

namespace hachkingtohach1\vennv\check\checks\fly;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityEffect;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class FlyG extends PacketCheck{

    private static array $fakeMapViolation = [];
    private static array $lastYDiff = [];

    public function getCloning() : int{
        return 2;
    }

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayInFlying) return;

        if($packet->isAllowed) return;
        
        $this->checkInfo(
            self::FLY, "G", "Fly", 5, $origin
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
        $fakeMapViolation->setMaxViolation(5);
        $fakeMapViolation->setMaxTicks(0.5);

        $joinTicks = $profile->getJoinTicks();

        $onGround = $profile->getOnGround();
        $onLiquid = $profile->isOnLiquid();

        $inVehicle = $profile->getInVehicle();

        $effects = $profile->getEffectHandler();

        $jumpLevel = 0;
        foreach($effects->getEffects() as $effect => $data){
            if($effect === VPacketPlayOutEntityEffect::JUMP){
                $jumpLevel = $data["amplifier"];
            }
        }

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        if(!$inVehicle && !$onLiquid && !$onGround && !$packet->onGround && !$location->isOnGround() && !$lastLocation->isOnGround() && $joinTicks > 2){
            $d1 = $lastLocation->getY() - $location->getY();
            $d2 = $d1 - 0.41999998688697815;
            if(
                !empty(self::$lastYDiff[$profile->getName()]) &&
                $lastLocation->getY() < $location->getY() && abs($d1 + 0.9800000190734863) > 1.0E-11 
                && abs($d1 + 0.09800000190735147) > 1.0E-11 && abs($d1 - 0.0030162615090425504) > 1.0E-9 
                || abs($d1 / 0.9800000190734863 + 0.08) > 1.0E-11 && abs($d2) > 9.999999960041972E-13
                && abs($d2 - $jumpLevel * 0.1) > 1.0000000116860974E-7 && abs($d1 + 0.15233518685055714) > 1.0E-11
                && (abs($d1 + 0.07242780368044421) > 1.0E-11 || max($lastLocation->getY(), $location->getY()) < 255.0)
            ){
                if(!isset(self::$lastYDiff[$profile->getName()])){
                    self::$lastYDiff[$profile->getName()] = 0;
                }
                $lastYDiff = self::$lastYDiff[$profile->getName()];
                $b = $location->getX() != $lastLocation->getX() && $location->getZ() != $lastLocation->getZ();
                $d3 = ($lastYDiff - 0.08) * 0.9800000190734863;
                if($lastLocation->isOnGround() && $d1 < 0.0 && $d3 < $d1 && ($location->distanceXZSquared($lastLocation->toVector()) < 0.0025 && $jumpLevel < 1)){
                    $d3 = $d1;
                }elseif($jumpLevel > 1 && abs($d3) < 0.005){
                    $d3 = 0.0;
                }
                $d4 = abs($d3 - $d1);
                $d5 = ($d3 - $d1) / $d3;
                if($d4 > 0.15 && abs($d5) > 300.0){
                    $this->handleViolation("D1: ".$d1." D2: ".$d2." D3: ".$d3." D4: ".$d4." D5: ".$d5." JL: ".$jumpLevel." B: ".$b." YD: ".$lastYDiff);
                }
                $class = new class extends PacketCheck{
                    public function handle(VPacket $packet, string $origin) : void{
                        $this->checkInfo(
                            self::FLY, "2G", "Fly", 5, $origin
                        );
                    }
                };
                $class->handle($packet, $origin);
                if($d4 < 0.1 && abs($d5) <= 1){
                    if($fakeMapViolation->handleViolation()){
                        $class->handleViolation("D1: ".$d1." D2: ".$d2." D3: ".$d3." D4: ".$d4." D5: ".$d5." JL: ".$jumpLevel." B: ".$b." YD: ".$lastYDiff);
                    }
                }else{
                    $fakeMapViolation->addViolation(-0.15);
                }
                unset(self::$lastYDiff[$profile->getName()]);
            }
        }
        if(!$lastLocation->isOnGround() || !$location->isOnGround()){
            self::$lastYDiff[$profile->getName()] = $lastLocation->getY() - $location->getY();
        }else{
            unset(self::$lastYDiff[$profile->getName()]);
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