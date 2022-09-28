<?php

namespace hachkingtohach1\vennv\check\checks\speed;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;

class SpeedA extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayOutPosition) return;
        
        $this->checkInfo(
            self::MOVE, "A", "Speed", 3, $origin
        );

        $profile = $this->getProfile();

        $ping = $profile->getPing();

        $onGround = $profile->getOnGround();

        $deathTicks = $profile->getDeathTicks();
        $respawnTicks = $profile->getRespawnTicks();
        $moveTicks = $profile->getMoveTicks();

        $deltaYaw = $profile->getDeltaYaw();

        $deltaXZ = $profile->getDeltaXZ();
        $lastDeltaXZ = $profile->getLastDeltaXZ();

        $accel = abs($deltaXZ - $lastDeltaXZ);

        $limit = 1E-5 - ($ping * 9E-9) - ($moveTicks * 9E-9);

        if($onGround && $deathTicks > 2 && $respawnTicks > 2 && $deltaYaw > 1.5 && $deltaXZ > 0.15 && $accel < $limit){
            $this->handleViolation("A: ".$accel);
        }else{
            $this->addViolation(-0.01);
        }
    }
}