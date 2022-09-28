<?php

namespace hachkingtohach1\vennv\check\checks\badpackets;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\packets\VPlayerActionPacket;
use hachkingtohach1\vennv\compat\VPacket;

class BadPacketsD extends PacketCheck{

    private static array $sentSprint = [];
    private static array $sentSneak = [];

    public function handle(VPacket $packet, string $origin) : void{     
        
        $this->checkInfo(
            self::BADPACKETS, "D", "BadPackets", 5, $origin
        );

        $profile = $this->getProfile();

        if(!isset(self::$sentSprint[$profile->getName()])){
            self::$sentSprint[$profile->getName()] = false;
        }

        if(!isset(self::$sentSneak[$profile->getName()])){
            self::$sentSneak[$profile->getName()] = false;
        }

        if($packet instanceof VPacketPlayOutPosition){
            if(!$packet->onGround){
                self::$sentSprint[$profile->getName()] = false;
                self::$sentSneak[$profile->getName()] = false;
            }
        }

        if($packet instanceof VPlayerActionPacket){
            if($packet->action === VPlayerActionPacket::START_SPRINT){
                if(self::$sentSprint[$profile->getName()]){
                    //$this->handleViolation("Sprint");
                }else{
                    //$this->addViolation(-0.5);
                }
                self::$sentSprint[$profile->getName()] = true;
            }
            if($packet->action === VPlayerActionPacket::STOP_SPRINT){
                self::$sentSprint[$profile->getName()] = false;
            }
            if($packet->action === VPlayerActionPacket::START_SNEAK){
                if(self::$sentSneak[$profile->getName()]){
                    $this->handleViolation("Sneak");
                }else{
                    $this->addViolation(-0.5);
                }
                self::$sentSneak[$profile->getName()] = true;
            }
            if($packet->action === VPlayerActionPacket::STOP_SNEAK){
                self::$sentSneak[$profile->getName()] = false;
            }
        }
    }
}