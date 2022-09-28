<?php

namespace hachkingtohach1\vennv\check\checks\badpackets;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInSteerVehicle;
use hachkingtohach1\vennv\compat\VPacket;

class BadPacketsC extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayInSteerVehicle) return;
        
        $this->checkInfo(
            self::BADPACKETS, "C", "BadPackets", 1, $origin
        );

        if(abs($packet->forward) > 0.9800000190734863 || abs($packet->strafe) > 0.9800000190734863){
            $this->handleViolation("F: ".$packet->forward." S: ".$packet->strafe);
        }
    }
}