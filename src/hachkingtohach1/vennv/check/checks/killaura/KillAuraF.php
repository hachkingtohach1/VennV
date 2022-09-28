<?php

namespace hachkingtohach1\vennv\check\checks\killaura;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInAttackEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\MathUtil;
use hachkingtohach1\vennv\utils\Vector;

class KillAuraF extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayInAttackEntity) return;
        
        $this->checkInfo(
            self::ATTACK, "F", "KillAura", 3, $origin
        );

        $profile = $this->getProfile();

        $location = $profile->getLocation();

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        $vectorA = new Vector();
        $vectorA->set($packet->originX, $packet->originY, $packet->originZ);

        $vectorB = new Vector();
        $vectorB->set($packet->targetX, $packet->targetY, $packet->targetZ);

        $d1 = MathUtil::getDistanceBetweenAngles360b($location->getYaw(), MathUtil::getRotationFormTwoVector($vectorA, $vectorB)["yaw"]);
        $d2 = MathUtil::getDistanceBetweenAngles360b($location->getPitch(), MathUtil::getRotationFormTwoVector($vectorA, $vectorB)["pitch"]);

        if($d1 < 0 || $d2 < 0){
            $this->handleViolation("d1: $d1, d2: $d2");
        }
    }
}