<?php

namespace hachkingtohach1\vennv\check\checks\inventory;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInTransaction;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;

class InventoryC extends PacketCheck{

    private static array $transacted = [];
    
    public function handle(VPacket $packet, string $origin) : void{

        $this->checkInfo(
            self::INVENTORY, "C", "Inventory", 5, $origin
        );

        $profile = $this->getProfile();

        $onGround = $profile->getOnGround();

        $moveTicks = $profile->getMoveTicks();

        if($packet instanceof VPacketPlayInTransaction){
            if($packet->slot > 9 && $onGround && $moveTicks < 1){              
                self::$transacted[$profile->getName()] = microtime(true);
            }
        }

        if($packet instanceof VPacketPlayOutPosition){
            if(isset(self::$transacted[$profile->getName()])){
                $transacted = self::$transacted[$profile->getName()];
                $diff = microtime(true) - $transacted;
                if($diff < 0.04){
                    $this->handleViolation("D: ".$diff);
                }else{
                    $this->addViolation(-0.01);
                }
                unset(self::$transacted[$profile->getName()]);
            }
        }
    }
}