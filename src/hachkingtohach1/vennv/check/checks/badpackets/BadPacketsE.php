<?php

namespace hachkingtohach1\vennv\check\checks\badpackets;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInArmAnimation;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockPlace;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;

class BadPacketsE extends PacketCheck{

    private static array $swings = [];
    private static array $places = [];

    public function handle(VPacket $packet, string $origin) : void{     
        
        $this->checkInfo(
            self::BADPACKETS, "E", "BadPackets", 1, $origin
        );

        $profile = $this->getProfile();

        if(!isset(self::$swings[$profile->getName()])){
            self::$swings[$profile->getName()] = 0;
        }

        if(!isset(self::$places[$profile->getName()])){
            self::$places[$profile->getName()] = 0;
        }

        if($packet instanceof VPacketPlayOutPosition){
            self::$swings[$profile->getName()] = 0;
            self::$places[$profile->getName()] = 0;
        }

        if($packet instanceof VPacketPlayInArmAnimation){
            self::$swings[$profile->getName()]++;
            if(self::$swings[$profile->getName()] > 200){
                $this->handleViolation("Swing");
            }
        }

        if($packet instanceof VPacketPlayInBlockPlace){
            self::$places[$profile->getName()]++;
            if(self::$places[$profile->getName()] > 200){
                $this->handleViolation("Place");
            }
        }
    }
}