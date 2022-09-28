<?php

namespace hachkingtohach1\vennv\check\checks\badpackets;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInRotation;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\MoveUtils;

class BadPacketsA extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{ 
        if(!$packet instanceof VPacketPlayInRotation) return;
        
        $this->checkInfo(
            self::BADPACKETS, "A", "BadPackets", 1, $origin
        );

        if($packet->pitch > MoveUtils::MAXIMUM_PITCH){
            $this->handleViolation("P: ".$packet->pitch);
        }
    }
}