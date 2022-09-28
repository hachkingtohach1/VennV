<?php

namespace hachkingtohach1\vennv\check\checks\interact;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockPlace;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\Cuboid;
use hachkingtohach1\vennv\utils\Vector;

class InteractA extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayInBlockPlace) return;
        
        $this->checkInfo(
            self::INTERACT, "A", "Interact", 10, $origin
        );

        $profile = $this->getProfile();

        $location = $profile->getLocation();

        $vector = new Vector();
        $vector->set($packet->x, $packet->y, $packet->z);

        $cuboid = new Cuboid();
        $cuboid->set(
            $location->getX(), $location->getY(), $location->getZ(), $location->getYaw(), $location->getPitch(),
            $packet->x, $packet->y, $packet->z, $location->getYaw(), $location->getPitch()
        );

        $distance = $cuboid->getReach();

        if($distance > 4.8){
            $this->handleViolation("D: ".$distance." Y: ".$packet->y." YL: ".$location->getY());
        }else{
            $this->addViolation(-0.5);
        }
    }
}