<?php

namespace hachkingtohach1\vennv\check\checks\badpackets;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockDig;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockPlace;
use hachkingtohach1\vennv\compat\VPacket;

class BadPacketsH extends PacketCheck{

    private static array $lastDigging = [];
    private static array $lastPlacing = [];

    public function handle(VPacket $packet, string $origin) : void{     
        
        $this->checkInfo(
            self::BADPACKETS, "H", "BadPackets", 10, $origin
        );

        $profile = $this->getProfile();

        if($packet instanceof VPacketPlayInBlockDig){
            self::$lastDigging[$profile->getName()] = microtime(true);
        }

        if($packet instanceof VPacketPlayInBlockPlace){
            self::$lastPlacing[$profile->getName()] = microtime(true);
        }

        if(isset(self::$lastDigging[$profile->getName()]) && isset(self::$lastPlacing[$profile->getName()])){
            $digging = self::$lastDigging[$profile->getName()];
            $placing = self::$lastPlacing[$profile->getName()];
            $diff = $placing - $digging;
            if($diff < 0.1){
                $this->handleViolation("D: ".$digging." P: ".$placing);
            }else{
                $this->addViolation(-1);
            }
            unset(self::$lastDigging[$profile->getName()]);
            unset(self::$lastPlacing[$profile->getName()]);
        }
    }
}