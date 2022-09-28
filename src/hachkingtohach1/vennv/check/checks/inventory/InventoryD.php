<?php

namespace hachkingtohach1\vennv\check\checks\inventory;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInTransaction;
use hachkingtohach1\vennv\compat\VPacket;

class InventoryD extends PacketCheck{
    
    public function handle(VPacket $packet, string $origin) : void{

        $this->checkInfo(
            self::INVENTORY, "D", "Inventory", 5, $origin
        );

        $profile = $this->getProfile();

        $onGround = $profile->getOnGround();

        $isSneaking = $profile->isSneaking();

        if($packet instanceof VPacketPlayInTransaction){
            if($packet->slot > 9 && $onGround && $isSneaking){              
                $this->handleViolation("T: ".microtime(true));
            }else{
                $this->addViolation(-1);
            }
        }
    }
}