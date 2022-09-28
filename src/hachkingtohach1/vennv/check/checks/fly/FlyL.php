<?php

namespace hachkingtohach1\vennv\check\checks\fly;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\VPacket;

class FlyL extends PacketCheck{

    private static array $lastYDiff = [];

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayInFlying) return;

        if($packet->isAllowed) return;
        
        $this->checkInfo(
            self::FLY, "L", "Fly", 5, $origin
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

        $onLiquid = $profile->isOnLiquid();

        $inVehicle = $profile->getInVehicle();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $joinTicks = $profile->getJoinTicks();
        $teleportTicks = $profile->getTeleportTicks();
        $moveTicks = $profile->getMoveTicks();
        $pingTicks = $profile->getPingTicks();

        if(!$inVehicle && !$onLiquid && $joinTicks > 2 && $teleportTicks > 2){
            $yDiff = $location->getY() - $lastLocation->getY();
            if(
                isset(self::$lastYDiff[$profile->getName()]) &&
                $location->isOnGround() && fmod(fmod(fmod($location->getY(), 0.5), 16.0), 1.0) != 0 &&
                fmod(fmod(fmod($location->getY(), 0.5), 64.0), 1.0) != 1 && fmod($location->getY(), 0.5) != 0 &&
                fmod($lastLocation->getY(), 1.0) != 0.41999998688697815 && fmod($lastLocation->getY(), 1.0) != 0 &&
                fmod(abs($location->getY()), 0.5) - 0.015555072702202466 > 1.0E-12 && fmod($location->getY(), 1.0) != 0.09375 
            ){
                if($moveTicks > $pingTicks){
                    if(!$lastLocation->isOnGround() && $yDiff < 0.078 && self::$lastYDiff[$profile->getName()] > 0.08){
                        $this->handleViolation("YD: ".$yDiff." LYD: ".self::$lastYDiff[$profile->getName()]);
                    }else{
                        $this->addViolation(-0.01);
                    }
                }
            }
            self::$lastYDiff[$profile->getName()] = $yDiff;
        }
    }
}