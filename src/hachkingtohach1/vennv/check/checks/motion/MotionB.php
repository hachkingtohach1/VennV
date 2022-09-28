<?php

namespace hachkingtohach1\vennv\check\checks\motion;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\MoveUtils;
use hachkingtohach1\vennv\utils\SampleList;

class MotionB extends PacketCheck{

    private static array $sampleList = [];

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayOutPosition) return;
        
        $this->checkInfo(
            self::MOVE, "B", "Motion", 1, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveSampleList($profile->getName())){
            $this->setSampleList($profile->getName());
        }

        $sampleList = $this->getSampleList($profile->getName());
        $sampleList->setMaxSample(10);

        $deltaXZ = $profile->getDeltaXZ();
        $lastDeltaXZ = $profile->getLastDeltaXZ();

        $prediction = $lastDeltaXZ * 0.91 + MoveUtils::JUMP_MOVEMENT_FACTOR;

        $diff = $deltaXZ - $prediction;

        $result = $sampleList->handleSample($diff);
        if(count($result) >= $sampleList->getMaxSample()){
            $i = 0;
            foreach($result as $data){
                if($data < -1){
                    $i++;
                }
            }
            if($i >= $sampleList->getMaxSample()){
                $this->handleViolation("D: ".$diff);
            }
        }
    }

    private function isHaveSampleList(string $profileName) :bool{
        return !empty(self::$sampleList[$profileName]);
    }

    private function getSampleList(string $profileName) : SampleList{
        return self::$sampleList[$profileName];
    }

    private function setSampleList(string $profileName) : void{
        self::$sampleList[$profileName] = new SampleList();
    }
}