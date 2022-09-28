<?php

namespace hachkingtohach1\vennv\check\checks\badpackets;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;

class BadPacketsF extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayOutPosition) return;
        
        $this->checkInfo(
            self::BADPACKETS, "F", "BadPackets", 1, $origin
        );

        if($packet->y >= 370){
            $this->handleViolation("Y: ".$packet->y);
        }
    }
}