<?php

namespace hachkingtohach1\vennv\check\checks\autoclick;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutSound;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\storage\StorageEngine;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class AutoClickC extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutSound) return; 
        
        $this->checkInfo(
            self::INTERACT, "C", "AutoClick", 10, $origin
        );

        $profile = $this->getProfile();

        $attackTicks = $profile->getAttackTicks();

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $maxCPS = StorageEngine::getInstance()->getConfig()->getData(StorageEngine::CHECK_SETTINGS_MAX_CPS) * 1.7;

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation((int)$maxCPS);
        $fakeMapViolation->setMaxTicks(2);

        if($packet->sound === VPacketPlayOutSound::ATTACK_NODAMAGE && $attackTicks < 3){
            if($fakeMapViolation->handleViolation()){
                $this->handleViolation("A: ".$maxCPS);
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
        return !empty(self::$fakeMapViolation[$profileName]);
    }
}