<?php

namespace hachkingtohach1\vennv\check\checks\aim;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInUseEntity;
use hachkingtohach1\vennv\compat\VPacket;

class AimE extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayInUseEntity) return;   
        
        $this->checkInfo(
            self::ATTACK, "E", "Aim", 5, $origin
        );

        $profile = $this->getProfile();

        $teleportTicks = $profile->getTeleportTicks();

        if($teleportTicks < 3){
            return;
        }

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $deltaYaw = abs($location->getYaw() - $lastLocation->getYaw());

        if($deltaYaw > 0.0065){
            $roundedDiff = abs(round($deltaYaw, 1) - round($deltaYaw, 5));
            if($roundedDiff <= 3E-5){
                $this->handleViolation("Y: ".$deltaYaw);
            }else{
                $this->addViolation(-0.5);
            }
        }
    }
}