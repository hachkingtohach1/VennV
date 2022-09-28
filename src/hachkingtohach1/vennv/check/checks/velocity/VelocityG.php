<?php

namespace hachkingtohach1\vennv\check\checks\velocity;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutUnderAttack;
use hachkingtohach1\vennv\compat\VPacket;

class VelocityG extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutUnderAttack) return;
        
        $this->checkInfo(
            self::MOVE, "G", "Velocity", 1, $origin
        );

        $profile = $this->getProfile();

        $onLiquid = $profile->isOnLiquid();
        $onWeb = $profile->isOnWeb();

        if($onLiquid || $onWeb){
            return;
        }

        $teleportTicks = $profile->getTeleportTicks();

        if($teleportTicks < 5){
            return;
        }

        $velocityY = $profile->getVelocityY();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $onGround = $profile->getOnGround();

        if($velocityY > 0 && !$onGround){
            $diffY = $lastLocation->getY() - $location->getY();
            if($diffY > 0 && $diffY < 0.06){
                $this->handleViolation("D: ".$diffY);
            }                     
        }
    }
}