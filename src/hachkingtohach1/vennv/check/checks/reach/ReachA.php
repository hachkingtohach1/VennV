<?php

namespace hachkingtohach1\vennv\check\checks\reach;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInAttackEntity;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\Cuboid;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class ReachA extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{ 
        if(!$packet instanceof VPacketPlayInAttackEntity) return;
        
        $this->checkInfo(
            self::ATTACK, "A", "Reach", 1, $origin
        );

        $profile = $this->getProfile();

        $ping = $profile->getPing();

        $moveTicks = $profile->getMoveTicks();

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(2);
        $fakeMapViolation->setMaxTicks(0.47);

        $cuboid = new Cuboid();
        $cuboid->set(
            $packet->targetX, $packet->targetY, $packet->targetZ, 0, 0,
            $packet->originX, $packet->originY, $packet->originZ, 0, 0
        );

        $limit = 3 + ($moveTicks * 17) + ($ping / 300) + abs($packet->originY - $packet->targetY) * 4;

        if($cuboid->getReach() > $limit){
            if($fakeMapViolation->handleViolation()){
                $this->handleViolation("R: ".$cuboid->getReach()." L: ".$limit." P: ".$ping." M: ".$moveTicks);
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