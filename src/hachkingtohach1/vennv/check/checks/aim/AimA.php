<?php

namespace hachkingtohach1\vennv\check\checks\aim;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;

class AimA extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutPosition) return;   
        
        $this->checkInfo(
            self::ATTACK, "A", "Aim", 3, $origin
        );

        $profile = $this->getProfile();

        $teleportTicks = $profile->getTeleportTicks();

        if($teleportTicks < 3){
            return;
        }

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $deltaYaw = abs($location->getYaw() - $lastLocation->getYaw());
        $deltaPitch = abs($location->getPitch() - $lastLocation->getPitch());

        if($deltaYaw >= 1 && fmod($deltaYaw, 0.1) == 0){
            if(fmod($deltaYaw, 1) == 0){
                $this->handleViolation("Y: ".$deltaYaw);
            }
            if(fmod($deltaYaw, 10) == 0){
                $this->handleViolation("Y: ".$deltaYaw);
            }
            if(fmod($deltaYaw, 30) == 0){
                $this->handleViolation("Y: ".$deltaYaw);
            }
        }

        if($deltaPitch >= 1 && fmod($deltaPitch, 0.1) == 0){
            if(fmod($deltaPitch, 1) == 0){
                $this->handleViolation("P: ".$deltaPitch);
            }
            if(fmod($deltaPitch, 10) == 0){
                $this->handleViolation("P: ".$deltaPitch);
            }
            if(fmod($deltaPitch, 30) == 0){
                $this->handleViolation("P: ".$deltaPitch);
            }
        }
    }
}