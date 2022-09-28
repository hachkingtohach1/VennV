<?php

namespace hachkingtohach1\vennv\check\checks\inventory;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInCloseWindow;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutOpenWindow;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class InventoryA extends PacketCheck{

    private static array $openWindow = [];
    private static array $fakeMapViolation = [];
    
    public function handle(VPacket $packet, string $origin) : void{

        $this->checkInfo(
            self::INVENTORY, "A", "Inventory", 5, $origin
        );

        $profile = $this->getProfile();

        $onGround = $profile->getOnGround();
        $onLiquid = $profile->isOnLiquid();
        $onIce = $profile->isOnIce();

        $joinTicks = $profile->getJoinTicks();

        if($joinTicks > 2 && $packet instanceof VPacketPlayOutOpenWindow){
            self::$openWindow[$profile->getName()] = true;
        }

        if($packet instanceof VPacketPlayInCloseWindow){
            self::$openWindow[$profile->getName()] = false;
        }

        if($packet instanceof VPacketPlayOutPosition){

            if(!isset(self::$openWindow[$profile->getName()])) return;

            if(!$this->isHaveFakeMapViolation($profile->getName())){
                $this->setFakeMapViolation($profile->getName());
            }

            $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
            $fakeMapViolation->setMaxTicks(0.3);

            if($onGround && !$onLiquid){
                if(self::$openWindow[$profile->getName()] === true){     
                    $limit = 9;
                    $limit += $onIce ? 2 : 0;    
                    if($fakeMapViolation->getTicks() >= $fakeMapViolation->getMaxTicks()){
                        if($fakeMapViolation->getViolations() >= $limit){
                            $this->handleViolation("V: ".$fakeMapViolation->getViolations());
                        }
                        $fakeMapViolation->debugTicks();
                        $fakeMapViolation->setViolation(0);
                    }else{
                        $fakeMapViolation->addViolation(1);
                    }
                }
            }
        }
    }

    public function setFakeMapViolation(string $profileName) : void{
        self::$fakeMapViolation[$profileName] = new FakeMapViolation();
    }

    public function getFakeMapViolation(string $profileName) : FakeMapViolation{
        return self::$fakeMapViolation[$profileName];
    }

    public function isHaveFakeMapViolation(string $profileName) : bool{
        return isset(self::$fakeMapViolation[$profileName]);
    }
}