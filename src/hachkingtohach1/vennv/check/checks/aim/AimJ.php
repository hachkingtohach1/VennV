<?php

namespace hachkingtohach1\vennv\check\checks\aim;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInAttackEntity;
use hachkingtohach1\vennv\compat\VPacket;

class AimJ extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayInAttackEntity) return;   
        
        $this->checkInfo(
            self::ATTACK, "J", "Aim", 5, $origin
        );

        $profile = $this->getProfile();

        $teleportTicks = $profile->getTeleportTicks();

        if($teleportTicks < 3){
            return;
        }

        $deltaYaw = $profile->getDeltaYaw();
        $deltaPitch = $profile->getDeltaPitch();

        $deltaXZ = $profile->getDeltaXZ();
        $lastDeltaXZ = $profile->getLastDeltaXZ();

        $accel = abs($deltaXZ - $lastDeltaXZ);

        if($accel < 1e-02 && $deltaYaw > 30 && $deltaPitch > 15){
            $this->handleViolation("A: ".$accel);
        }else{
            $this->addViolation(-0.5);
        }
    }
}