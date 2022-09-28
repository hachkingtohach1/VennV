<?php

namespace hachkingtohach1\vennv\check\checks\killaura;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockPlace;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInUseEntity;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class KillAuraK extends PacketCheck{

    private static array $placed = [];
    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{

        $this->checkInfo(
            self::ATTACK, "K", "KillAura", 3, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(8);
        $fakeMapViolation->setMaxTicks(0.4);

        if($packet instanceof VPacketPlayInBlockPlace){
            self::$placed[$profile->getName()] = true;
        }

        if($packet instanceof VPacketPlayInUseEntity){
            if(isset(self::$placed[$profile->getName()])){
                if($fakeMapViolation->handleViolation()){
                    $this->handleViolation("T: ".microtime(true));
                }
                unset(self::$placed[$profile->getName()]);
            }
        }
    }

    private function getFakeMapViolation(string $profileName) : FakeMapViolation{
        return self::$fakeMapViolation[$profileName];
    }

    private function setFakeMapViolation(string $profileName) : void{
        self::$fakeMapViolation[$profileName] = new FakeMapViolation();
    }

    private function isHaveFakeMapViolation(string $profileName) :bool{
        return isset(self::$fakeMapViolation[$profileName]);
    }
}