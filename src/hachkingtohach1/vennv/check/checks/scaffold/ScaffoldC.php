<?php

namespace hachkingtohach1\vennv\check\checks\scaffold;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockPlace;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\Cuboid;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class ScaffoldC extends PacketCheck{

    private static array $fakeMapViolation = [];
    
    public function handle(VPacket $packet, string $origin) : void{

        $this->checkInfo(
            self::INTERACT, "C", "Scaffold", 3, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(4);
        $fakeMapViolation->setTicks(0.5);

        $location = $profile->getLocation();

        if($packet instanceof VPacketPlayInBlockPlace){
            
            $cuboid = new Cuboid();
            $cuboid->set(
                $location->getX(), $location->getY(), $location->getZ(), 0, 0,
                $packet->x, $packet->y, $packet->z, 0 ,0    
            );

            $reach = $cuboid->getReach();

            if($location->getPitch() < 40 && $reach < 2){
                if($fakeMapViolation->handleViolation()){
                    $this->handleViolation("P: ".$location->getPitch()." R: ".$reach);
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