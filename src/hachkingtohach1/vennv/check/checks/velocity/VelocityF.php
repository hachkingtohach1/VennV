<?php

namespace hachkingtohach1\vennv\check\checks\velocity;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutUnderAttack;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\SampleList;

class VelocityF extends PacketCheck{

    private static array $sampleList = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutUnderAttack) return;
        
        $this->checkInfo(
            self::MOVE, "F", "Velocity", 1, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveSampleList($profile->getName())){
            $this->setSampleList($profile->getName());
        }

        $sampleList = $this->getSampleList($profile->getName());
        $sampleList->setMaxSample(15);

        $onLiquid = $profile->isOnLiquid();
        $onWeb = $profile->isOnWeb();

        if($onLiquid || $onWeb){
            return;
        }

        $teleportTicks = $profile->getTeleportTicks();

        if($teleportTicks < 5){
            return;
        }

        $velocityY = $profile->getVelocityY();

        $onGround = $profile->getOnGround();

        if($velocityY <= 0 && $onGround){
            $i = 0;
            $result = $sampleList->handleSample($velocityY);
            if(count($result) >= $sampleList->getMaxSample()){
                foreach($result as $sample){
                    if($sample == -0.08){
                        $i++;
                    }
                }
            }
            if($i >= $sampleList->getMaxSample()){
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