<?php

namespace hachkingtohach1\vennv\check\checks\motion;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\MoveUtils;

class MotionA extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayOutPosition) return;
        
        $this->checkInfo(
            self::MOVE, "A", "Motion", 3, $origin
        );

        $profile = $this->getProfile();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();
        $lastLastLocation = $profile->getLastLastLocation();

        if($location->isOnGround() && ($lastLocation->isOnGround() || $lastLastLocation->isOnGround())){
            $deltaX = $location->getX() - $lastLocation->getX();
            $deltaY = $location->getY() - $lastLocation->getY();
            $deltaZ = $location->getZ() - $lastLocation->getZ();

            $deltaXZ = sqrt($deltaX ** 2 + $deltaZ ** 2);

            $limit = MoveUtils::MOTION_Y_FRICTION;

            if($deltaY > $limit && $deltaXZ < 0.1){
                $this->handleViolation("Y: $deltaY, XZ: $deltaXZ");
            }else{
                $this->addViolation(-0.02);
            }
        }
    }
}