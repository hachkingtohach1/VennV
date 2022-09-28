<?php

namespace hachkingtohach1\vennv\check\checks\badpackets;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInArmAnimation;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInUseEntity;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class BadPacketsB extends PacketCheck{

    private static array $armAnimationReceived = [];
    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{     
        
        $this->checkInfo(
            self::BADPACKETS, "B", "BadPackets", 5, $origin
        );

        $profile = $this->getProfile();

        $ping = $profile->getPing();

        if($packet instanceof VPacketPlayInArmAnimation){
            self::$armAnimationReceived[$profile->getName()] = microtime(true);
        }

        if($packet instanceof VPacketPlayInUseEntity){

            if(!$this->isHaveFakeMapViolation($profile->getName())){
                $this->setFakeMapViolation($profile->getName());
            }
    
            $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
            $fakeMapViolation->setMaxViolation(4);
            $fakeMapViolation->setMaxTicks(0.5);

            if(!isset(self::$armAnimationReceived[$profile->getName()])){
                self::$armAnimationReceived[$profile->getName()] = microtime(true);
            }

            $abs = abs(microtime(true) - self::$armAnimationReceived[$profile->getName()]);
            if($abs > 1.5 + ($ping / 100)){
                if($fakeMapViolation->handleViolation()){
                    $this->handleViolation("A: ".$abs);
                }
            }else{
                $fakeMapViolation->setViolation(0);
                $fakeMapViolation->setTicks(microtime(true));
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