<?php

namespace hachkingtohach1\vennv\check\checks\jesus;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\VPacket;

class JesusA extends PacketCheck{

    private static array $firstInWater = [];

    public function handle(VPacket $packet, string $origin) : void{     
        
        $this->checkInfo(
            self::MOVE, "A", "Jesus", 5, $origin
        );

        $profile = $this->getProfile();

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        $onLiquid = $profile->isOnLiquid();

        $speed = $profile->getSpeed();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();
        $lastLastLocation = $profile->getLastLastLocation();

        $deltaX = $profile->getDeltaX();
        $lastDeltaX = $profile->getLastDeltaX();

        $teleportTicks = $profile->getTeleportTicks();

        if($packet instanceof VPacketPlayInFlying){
            if($packet->isAllowed){
                $this->addViolation(-0.1);
            }
        }

        if($deltaX != 0 && $lastDeltaX != 0 && $onLiquid && !$location->isOnGround() && !$lastLocation->isOnGround() && !$lastLastLocation->isOnGround()){
            if($teleportTicks > 2){
                if(!isset(self::$firstInWater[$profile->getName()])){
                    self::$firstInWater[$profile->getName()] = microtime(true);
                    return;
                }
                $limit = $speed * 25;
                $distX = $location->getX() - $lastLocation->getX();
                $distZ = $location->getZ() - $lastLocation->getZ();
                $dist = ($distX * $distX) + ($distZ * $distZ);
                $lastDist = $dist;
                $shiftedLastDist = $lastDist * 0.91;
                $equalness = $dist - $shiftedLastDist;
                $scaledEqualness = $equalness * 138;  
                if($scaledEqualness > $limit){
                    $this->handleViolation("SC: ".$scaledEqualness);
                }else{
                    $this->addViolation(-0.1);
                }
            }
        }else{
            unset(self::$firstInWater[$profile->getName()]);
        }
    }
}