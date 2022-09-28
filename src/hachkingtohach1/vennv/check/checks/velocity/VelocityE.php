<?php

namespace hachkingtohach1\vennv\check\checks\velocity;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutUnderAttack;
use hachkingtohach1\vennv\compat\VPacket;

class VelocityE extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutUnderAttack) return;
        
        $this->checkInfo(
            self::MOVE, "E", "Velocity", 1, $origin
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

        $deathTicks = $profile->getDeathTicks();
        $respawnTicks = $profile->getRespawnTicks();

        $deltaYaw = $profile->getDeltaYaw();

        $deltaXZ = $profile->getDeltaXZ();
        $lastDeltaXZ = $profile->getLastDeltaXZ();

        $accel = abs($deltaXZ - $lastDeltaXZ);

        $doubt = 1E-5;

        if($moveTicks > 1){
            $doubt += $moveTicks *  0.03;
        }

        if($onGround && $deathTicks > 1.5 && $respawnTicks > 1.5 && $deltaYaw > 1.5 && $deltaXZ > 0.15 && $accel < $doubt){
            $this->handleViolation("A: ".$accel." D: ".$doubt);
        }
    }
}