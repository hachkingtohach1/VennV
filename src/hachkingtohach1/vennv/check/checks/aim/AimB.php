<?php

namespace hachkingtohach1\vennv\check\checks\aim;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;

class AimB extends PacketCheck{

    private static array $lastYawChange = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutPosition) return;   
        
        $this->checkInfo(
            self::ATTACK, "B", "Aim", 3, $origin
        );

        $profile = $this->getProfile();

        $teleportTicks = $profile->getTeleportTicks();

        if($teleportTicks < 3){
            return;
        }

        if(!isset(self::$lastYawChange[$profile->getName()])){
            self::$lastYawChange[$profile->getName()] = 0;
        }

        $attackTicks = $profile->getAttackTicks();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $abs = abs($location->getYaw() - $lastLocation->getYaw());

        if($attackTicks < 1.5 && $abs > 1 && $round = round($abs) == $abs){
            if($abs == self::$lastYawChange[$profile->getName()]){
                $this->handleViolation("Y: ".$abs);
            }
            self::$lastYawChange[$profile->getName()] = $round;
        }else{
            self::$lastYawChange[$profile->getName()] = 0;
        }
    }
}