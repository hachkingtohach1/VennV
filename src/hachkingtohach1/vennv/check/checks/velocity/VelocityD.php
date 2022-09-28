<?php

namespace hachkingtohach1\vennv\check\checks\velocity;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutUnderAttack;
use hachkingtohach1\vennv\compat\VPacket;

class VelocityD extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutUnderAttack) return;
        
        $this->checkInfo(
            self::MOVE, "D", "Velocity", 1, $origin
        );

        $profile = $this->getProfile();

        $onLiquid = $profile->isOnLiquid();
        $onWeb = $profile->isOnWeb();

        if($onLiquid || $onWeb){
            return;
        }

        $teleportTicks = $profile->getTeleportTicks();
        $joinTicks = $profile->getJoinTicks();

        if($teleportTicks < 5){
            return;
        }

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $offsetY = $location->getY() - $lastLocation->getY();

        $velocityY = $profile->getVelocityY();

        $onGround = $profile->getOnGround();

        if($joinTicks > 5 && $velocityY > 0 && $onGround && fmod($lastLocation->getY(), 1) == 0 && $offsetY > 0.0 && $offsetY < 0.41999998688697815){
            $ratioY = $offsetY / $velocityY;
            if($ratioY < 0.99){
                if($ratioY < 0.6){
                    $this->handleViolation("R1: ".$ratioY);
                }elseif($ratioY > 0.6){
                    $this->handleViolation("R2: ".$ratioY);
                }
            }
        }
    }
}