<?php

namespace hachkingtohach1\vennv\check\checks\inventory;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInCloseWindow;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInTransaction;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutOpenWindow;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class InventoryB extends PacketCheck{

    private static array $lastSlot = [];
    private static array $openWindow = [];
    private static array $fakeMapViolation = [];
    
    public function handle(VPacket $packet, string $origin) : void{

        $this->checkInfo(
            self::INVENTORY, "B", "Inventory", 5, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxTicks(1);

        if($packet instanceof VPacketPlayOutOpenWindow){
            self::$openWindow[$profile->getName()] = true;
        }

        if($packet instanceof VPacketPlayInCloseWindow){
            self::$openWindow[$profile->getName()] = false;
        }

        if(!isset(self::$lastSlot[$profile->getName()])){
            self::$lastSlot[$profile->getName()] = 0;
        }

        if(!isset(self::$openWindow[$profile->getName()])) return;

        $openWindow = self::$openWindow[$profile->getName()];
        $lastSlot = self::$lastSlot[$profile->getName()];

        if($openWindow && $packet instanceof VPacketPlayInTransaction){

            if($packet->slot !== $lastSlot){
                $fakeMapViolation->addViolation(1);
                self::$lastSlot[$profile->getName()] = $packet->slot;
            }
            
            if($fakeMapViolation->getTicks() >= $fakeMapViolation->getMaxTicks()){
                if($fakeMapViolation->getViolations() >= 25){
                    $this->handleViolation("LS: ".$lastSlot." MCRT:".microtime(true));
                }
                $fakeMapViolation->debugTicks();
                $fakeMapViolation->setViolation(0);
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