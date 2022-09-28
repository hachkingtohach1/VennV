<?php

namespace hachkingtohach1\vennv\check\checks\reach;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInAttackEntity;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\Cuboid;

class ReachC extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{ 
        if(!$packet instanceof VPacketPlayInAttackEntity) return;
        
        $this->checkInfo(
            self::ATTACK, "C", "Reach", 2, $origin
        );

        $profile = $this->getProfile();

        $ping = $profile->getPing();

        $moveTicks = $profile->getMoveTicks();

        $cuboid = new Cuboid();
        $cuboid->set(
            $packet->targetX, $packet->targetY, $packet->targetZ, 0, 0,
            $packet->originX, $packet->originY, $packet->originZ, 0, 0
        );

        $limit = 5.3 + ($moveTicks * 0.5) + ($ping / 300);

        if($cuboid->getReach() >= $limit){
            $this->handleViolation("R: ".$cuboid->getReach()." L: ".$limit);
        }
    }
}