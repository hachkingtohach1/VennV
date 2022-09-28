<?php

namespace hachkingtohach1\vennv\check\checks\nofall;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;

class NoFallA extends PacketCheck{

    private static array $lastGround = [];

    public function handle(VPacket $packet, string $origin) : void{  
        if(!$packet instanceof VPacketPlayOutPosition) return;  
        
        $this->checkInfo(
            self::MOVE, "A", "NoFall", 2, $origin
        );

        $profile = $this->getProfile();

        $onGround = $packet->y % 1/64 < 0.0001;

        if(isset(self::$lastGround[$profile->getName()])){
            $lastGround = self::$lastGround[$profile->getName()];
            if($lastGround != $packet->onGround){
                //$this->handleViolation("Ground: ".$onGround);
            }
            unset(self::$lastGround[$profile->getName()]);
        }

        self::$lastGround[$profile->getName()] = $onGround;
    }
}