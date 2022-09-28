<?php

namespace hachkingtohach1\vennv\check\checks\reach;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInAttackEntity;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\Cuboid;
use hachkingtohach1\vennv\utils\PingUtil;
use hachkingtohach1\vennv\utils\SampleList;

class ReachB extends PacketCheck{

    private static array $sampleList = [];

    public function handle(VPacket $packet, string $origin) : void{ 
        if(!$packet instanceof VPacketPlayInAttackEntity) return;
        
        $this->checkInfo(
            self::ATTACK, "B", "Reach", 2, $origin
        );

        $profile = $this->getProfile();

        $ping = $profile->getPing();

        $moveTicks = $profile->getMoveTicks();

        if(!$this->isHaveSampleList($profile->getName())){
            $this->setSampleList($profile->getName());
        }

        $sampleList = $this->getSampleList($profile->getName());
        $sampleList->setMaxSample(10);

        $cuboid = new Cuboid();
        $cuboid->set(
            $packet->targetX, $packet->targetY, $packet->targetZ, 0, 0,
            $packet->originX, $packet->originY, $packet->originZ, 0, 0
        );
        
        $add = ($moveTicks * 23) + ($ping / 300) + abs($packet->originY - $packet->targetY) * 4;

        $i = 0;
        $result = $sampleList->handleSample($cuboid->getReach() - $add);
        if(count($result) >= $sampleList->getMaxSample()){
            foreach($result as $sample){
                if($sample >= 3){
                    $i++;
                }
            }
            if($i >= $sampleList->getMaxSample()/2){
                $this->handleViolation("I: ".$i);
            }
        }
    }

    private function getSampleList(string $profileName) : SampleList{
        return self::$sampleList[$profileName];
    }

    private function setSampleList(string $profileName) : void{
        self::$sampleList[$profileName] = new SampleList();
    }

    private function isHaveSampleList(string $profileName) :bool{
        return isset(self::$sampleList[$profileName]);
    }
}