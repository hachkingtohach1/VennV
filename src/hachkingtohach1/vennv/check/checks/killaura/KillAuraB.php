<?php

namespace hachkingtohach1\vennv\check\checks\killaura;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInUseEntity;
use hachkingtohach1\vennv\compat\VPacket;

class KillAuraB extends PacketCheck{

    private static array $attack = [];
    private static array $interactAt = [];
    private static array $interact = [];

    public function handle(VPacket $packet, string $origin) : void{
        
        $this->checkInfo(
            self::ATTACK, "B", "KillAura", 1, $origin
        );

        $profile = $this->getProfile();

        if(!isset(self::$attack[$profile->getName()])){
            self::$attack[$profile->getName()] = false;
        }

        if(!isset(self::$interactAt[$profile->getName()])){
            self::$interactAt[$profile->getName()] = false;
        }

        if(!isset(self::$interact[$profile->getName()])){
            self::$interact[$profile->getName()] = false;
        }

        if($packet instanceof VPacketPlayInUseEntity){
            if($packet->action === VPacketPlayInUseEntity::ACTION_ATTACK){
                if(
                    !self::$attack[$profile->getName()] && 
                    (self::$interactAt[$profile->getName()] || self::$interact[$profile->getName()])
                ){
                    $this->handleViolation("Attack");
                    self::$interactAt[$profile->getName()] = false;
                    self::$interact[$profile->getName()] = false;
                }
                self::$attack[$profile->getName()] = true;
            }elseif($packet->action === VPacketPlayInUseEntity::ACTION_ITEM_INTERACT){
                self::$interact[$profile->getName()] = true;
            }elseif($packet->action === VPacketPlayInUseEntity::ACTION_INTERACT){
                if(
                    self::$interact[$profile->getName()] &&
                    !self::$interactAt[$profile->getName()]
                ){
                    $this->handleViolation("Interact");
                    self::$interact[$profile->getName()] = false;
                }
                self::$interactAt[$profile->getName()] = true;
            }
        }
    }
}