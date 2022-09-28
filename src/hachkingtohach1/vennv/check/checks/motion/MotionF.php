<?php

namespace hachkingtohach1\vennv\check\checks\motion;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;

class MotionF extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayOutPosition) return;
        
        $this->checkInfo(
            self::MOVE, "F", "Motion", 5, $origin
        );

        $profile = $this->getProfile();

        if($profile->getPlacingBlock()){
            $profile->setPlacingBlock(false);
            return;
        }

        $deltaXZ = $profile->getDeltaXZ();
        $lastDeltaXZ = $profile->getLastDeltaXZ();

        $accel = abs($deltaXZ - $lastDeltaXZ);

        if($deltaXZ < 0.15 && $deltaXZ > 0.1 && $lastDeltaXZ > 0.15 && $accel < 0.1 && $accel > 0.099 && $packet->onGround){
            $this->handleViolation("A: ".$accel." D: ".$deltaXZ." LD: ".$lastDeltaXZ);
        }else{
            $this->addViolation(-0.1);
        }
    }
}