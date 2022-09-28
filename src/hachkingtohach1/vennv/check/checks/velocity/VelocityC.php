<?php

namespace hachkingtohach1\vennv\check\checks\velocity;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutUnderAttack;
use hachkingtohach1\vennv\compat\VPacket;

class VelocityC extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutUnderAttack) return;
        
        $this->checkInfo(
            self::MOVE, "C", "Velocity", 5, $origin
        );

        $profile = $this->getProfile();

        $onLiquid = $profile->isOnLiquid();
        $onWeb = $profile->isOnWeb();

        if($onLiquid || $onWeb){
            return;
        }

        $onGround = $profile->getOnGround();

        $pingTicks = $profile->getPingTicks();
        $maxPingTicks = $profile->getMaxPingTicks();

        $verticalVelocityTicks = $profile->getVerticalVelocityTicks();

        $moveTicks = $profile->getMoveTicks();
        $teleportTicks = $profile->getTeleportTicks();

        if($teleportTicks < 5){
            return;
        }

        $location = $profile->getLocation();
        $lastLastLocation = $profile->getLastLastLocation();    

        $lastVelY = $profile->getLastVelocityY();     

        if(!$onGround && $verticalVelocityTicks > $moveTicks - 1 && $lastVelY > 0){           
            $n = $location->getY() - $lastLastLocation->getY();
            if($n > 0){
                $n2 = ceil($n * 8000) / 8000;
                if($n2 < 0.41999998688697815 && $lastLastLocation->isOnGround() && !$location->isOnGround() && $pingTicks < $maxPingTicks){
                    $n3 = $n2 / $lastVelY;
                    if($n3 < 0.995){
                        $this->handleViolation("P3: ".$n3);
                    }
                }
                $profile->setVelocityY(0);
            }else{
                $this->addViolation(-0.01);
            }
        }
    }
}