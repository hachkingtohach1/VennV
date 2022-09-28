<?php

namespace hachkingtohach1\vennv\check\checks\velocity;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutUnderAttack;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\Location;
use hachkingtohach1\vennv\utils\Vector;

class VelocityA extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutUnderAttack) return;
        
        $this->checkInfo(
            self::MOVE, "A", "Velocity", 1.5, $origin
        );

        $profile = $this->getProfile();

        $onLiquid = $profile->isOnLiquid();
        $onWeb = $profile->isOnWeb();

        if($onLiquid || $onWeb){
            return;
        }

        $onGround = $profile->getOnGround();

        $moveTicks = $profile->getMoveTicks();
        $teleportTicks = $profile->getTeleportTicks();

        if($teleportTicks < 5){
            return;
        }

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();
        $lastLastLocation = $profile->getLastLastLocation();

        $deltaY = $profile->getDeltaY();

        $lastVelocityX = $profile->getLastVelocityX();
        $lastVelocityZ = $profile->getLastVelocityZ();

        $horizontalVelocityTicks = $profile->getHorizontalVelocityTicks();

        if($lastVelocityX !== 0 && $lastVelocityZ !== 0 && $horizontalVelocityTicks > $moveTicks){
            if($deltaY > 0){
                $deltaVelocityXZ = hypot($lastVelocityX, $lastVelocityZ);
                if($lastLastLocation->isOnGround() && !$lastLocation->isOnGround() && $location->isOnGround()){
                    $newLocation = new Location();
                    $newLocation->set();
                    if($onGround){
                        $vector = new Vector();
                        $vector->set($lastLastLocation->getX(), $lastLastLocation->getY(), $lastLastLocation->getZ());
                        $vector->subtract($location->getX(), $location->getY(), $location->getZ());
                        $newLocation = $newLocation->add($vector->getX(), $vector->getY(), $vector->getZ());
                    }else{
                        $newLocation = $lastLocation;
                    }
                    $xz = hypot($lastLocation->getX() - $location->getX(), $lastLocation->getZ() - $location->getZ());
                    $xz2 = hypot($newLocation->getX() - $location->getX(), $newLocation->getZ() - $location->getZ());
                    $xz3 = max($xz, $xz2) / $deltaVelocityXZ;
                    if($xz3 != 0 && $xz3 < 1 && !$onGround){
                        $this->handleViolation("xz2: ".$xz2." xz3: ".$xz3, 0.2);
                    }else{
                        $this->addViolation(-0.1);
                    }
                }
            }
        }
    }
}