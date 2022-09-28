<?php

namespace hachkingtohach1\vennv\check\checks\interact;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockPlace;
use hachkingtohach1\vennv\compat\VPacket;

class InteractB extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayInBlockPlace) return;
        
        $this->checkInfo(
            self::INTERACT, "B", "Interact", 10, $origin
        );

        $profile = $this->getProfile();

        $location = $profile->getLocation();

        $distance = abs($packet->y - $location->getY());

        if($distance > 6.5){
            $this->handleViolation("D: ".$distance." Y: ".$packet->y." YL: ".$location->getY());
        }else{
            $this->addViolation(-0.5);
        }
    }
}