<?php

namespace hachkingtohach1\vennv\check\checks\killaura;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInCloseWindow;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInUseEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutOpenWindow;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class KillAuraL extends PacketCheck{

    private static array $openWindow = [];
    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{
        
        $this->checkInfo(
            self::ATTACK, "L", "KillAura", 5, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(7);
        $fakeMapViolation->setMaxTicks(0.5);

        $joinTicks = $profile->getJoinTicks();

        if($joinTicks > 2 && $packet instanceof VPacketPlayOutOpenWindow){
            self::$openWindow[$profile->getName()] = true;
        }

        if($packet instanceof VPacketPlayInCloseWindow){
            self::$openWindow[$profile->getName()] = false;
        }

        if(
            isset(self::$openWindow[$profile->getName()]) &&
            $packet instanceof VPacketPlayInUseEntity
        ){
            if(self::$openWindow[$profile->getName()] === true){
                if($fakeMapViolation->handleViolation()){
                    $this->handleViolation("V: ".$fakeMapViolation->getViolations());
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