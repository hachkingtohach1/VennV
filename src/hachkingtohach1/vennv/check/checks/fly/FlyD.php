<?php

namespace hachkingtohach1\vennv\check\checks\fly;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityEffect;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class FlyD extends PacketCheck{

    private static array $airTicks = [];
    private static array $threshold = [];
    private static array $fakeMapViolation = [];

    public function getCloning() : int{
        return 2;
    }

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayInFlying) return;

        if($packet->isAllowed) return;
        
        $this->checkInfo(
            self::FLY, "D", "Fly", 3, $origin
        );

        $profile = $this->getProfile();

        if($profile->getPlacingBlock()){
            $profile->setPlacingBlock(false);
            return;
        }

        if(!isset(self::$airTicks[$profile->getName()])){
            self::$airTicks[$profile->getName()] = 0;
        }

        if(!isset(self::$threshold[$profile->getName()])){
            self::$threshold[$profile->getName()] = 0;
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
        $fakeMapViolation->setMaxViolation(2);
        $fakeMapViolation->setMaxTicks(0.5);

        $onGround = $profile->getOnGround();
        $onLiquid = $profile->isOnLiquid();

        $inVehicle = $profile->getInVehicle();

        $deltaY = $profile->getDeltaY();
        $lastDeltaY = $profile->getLastDeltaY();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();
        $lastLastLocation = $profile->getLastLastLocation();

        $joinTicks = $profile->getJoinTicks();
        $teleportTicks = $profile->getTeleportTicks();
        $underAttackTicks = $profile->getUnderAttackTicks();

        $effects = $profile->getEffectHandler();

        $add = 0;
        foreach($effects->getEffects() as $effect => $data){
            if($effect === VPacketPlayOutEntityEffect::JUMP){
                $add += $data["amplifier"] * 0.1;
            }
        }

        if($teleportTicks < 7 || $underAttackTicks < 2 || $joinTicks < 2.5){
            unset(self::$airTicks[$profile->getName()]);
            unset(self::$threshold[$profile->getName()]);
            return;
        }

        if(!$inVehicle && !$onLiquid && !$onGround && !$location->isOnGround() && !$lastLocation->isOnGround() && !$lastLastLocation->isOnGround()){
            if(self::$airTicks[$profile->getName()]++ > 15 && $deltaY > $lastDeltaY + (abs($lastDeltaY) / 1.1) + $add){
                if(self::$threshold[$profile->getName()]++ > 3){
                    $this->handleViolation("D: ".$deltaY." LD: ".$lastDeltaY);
                }
            }
        }else{
            self::$airTicks[$profile->getName()] = 0;
            self::$threshold[$profile->getName()] -= self::$threshold[$profile->getName()] > 0 ? 0.1 : 0;
        }

        $accel = abs($deltaY - $lastDeltaY);
        if(self::$airTicks[$profile->getName()] > 15 && $accel < 0.0002){
            if(self::$threshold[$profile->getName()]++ > 15){
                $class = new class extends PacketCheck{
                    public function handle(VPacket $packet, string $origin) : void{
                        $this->checkInfo(
                            self::FLY, "2D", "Fly", 3, $origin
                        );
                    }
                };
                $class->handle($packet, $origin);
                if($accel == 0){   
                    if($fakeMapViolation->handleViolation()){
                        $class->handleViolation("D: ".$deltaY." LD: ".$lastDeltaY);
                    }                 
                }else{
                    $class->addViolation(-0.05);
                }
                if($accel < 0){
                    $this->handleViolation("A: ".$accel);
                }
            }
            self::$threshold[$profile->getName()]++;
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