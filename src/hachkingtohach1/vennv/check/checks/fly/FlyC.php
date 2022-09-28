<?php

namespace hachkingtohach1\vennv\check\checks\fly;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInUseEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class FlyC extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{ 
        if(!$packet instanceof VPacketPlayInFlying) return;

        if($packet->isAllowed) return;    
        
        $this->checkInfo(
            self::FLY, "C", "Fly", 5, $origin
        );

        $profile = $this->getProfile();

        $pingTicks = $profile->getPingTicks();
        $maxPingTicks = $profile->getMaxPingTicks();

        $onGround = $profile->getOnGround();

        $inVehicle = $profile->getInVehicle();

        if($packet instanceof VPacketPlayInUseEntity){

            if(!$this->isHaveFakeMapViolation($profile->getName())){
                $this->setFakeMapViolation($profile->getName());
            }
    
            $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
            $fakeMapViolation->setMaxViolation(10);
            $fakeMapViolation->setMaxTicks(0.5);

            if(!$inVehicle && !$onGround && $pingTicks < 2 && $maxPingTicks > 1){
                if($fakeMapViolation->handleViolation()){
                    $this->handleViolation("This is the absolute sure check!");
                }
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