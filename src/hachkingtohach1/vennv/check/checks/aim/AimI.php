<?php

namespace hachkingtohach1\vennv\check\checks\aim;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;

class AimI extends PacketCheck{

    private static array $lastYaw = [];
    private static array $lastPitch = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutPosition) return; 
        
        $this->checkInfo(
            self::ATTACK, "I", "Aim", 5, $origin
        );

        $profile = $this->getProfile();

        $teleportTicks = $profile->getTeleportTicks();

        if($teleportTicks < 3){
            return;
        }

        $location = $profile->getLocation();

        if(
            isset(self::$lastYaw[$profile->getName()]) &&
            isset(self::$lastPitch[$profile->getName()])
        ){
            $lastYaw = self::$lastYaw[$profile->getName()];
            $pitch = abs($location->getYaw() - $lastYaw) % 180;
            self::$lastYaw[$profile->getName()] = $location->getYaw();
            self::$lastPitch[$profile->getName()] = round($pitch * 10) * 0.1;

            if($location->getYaw() < 0.1) return;

            $lastPitch = self::$lastPitch[$profile->getName()];

            if($pitch > 1 && round($pitch * 10) * 0.1 == $pitch && round($pitch) != $pitch){
                if($pitch == $lastPitch){
                    $this->handleViolation("P: ".$pitch." LP: ".$lastPitch);
                }
            }
        }else{
            self::$lastYaw[$profile->getName()] = $location->getYaw();
            self::$lastPitch[$profile->getName()] = $location->getPitch();
        }
    }
}