<?php

namespace hachkingtohach1\vennv\check\checks\timer;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketSentFrequently;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\php\LinkedList;

class TimerA extends PacketCheck{

    private static array $lastPacketTime = [];
    private static array $linkedList = [];

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketSentFrequently) return;
        
        $this->checkInfo(
            self::MISC, "A", "Timer", 2, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveLinkedList($profile->getName())){
            $this->setLinkedList($profile->getName());
        }

        $delays = $this->getLinkedList($profile->getName());

        $ping = $profile->getPing();

        $onGround = $profile->getOnGround();

        $joinTicks = $profile->getJoinTicks();
        $moveTicks = $profile->getMoveTicks();

        $deathTicks = $profile->getDeathTicks();
        $respawnTicks = $profile->getRespawnTicks();

        if($joinTicks > 3){
            if(!$this->isHaveLastPacketTime($profile->getName())){
                $this->setLastPacketTime($profile->getName(), microtime(true));
            }
            if($deathTicks > 2 && $respawnTicks > 2 && !$onGround){
                $limit = 600 + ($moveTicks * 200) + ($ping / 20);
                $delays->add(microtime(true) - $this->getLastPacketTime($profile->getName()));               
                if($delays->size() >= 40){
                    $average = 0;
                    foreach($delays->toArrayFirst() as $item){
                        $average += (int)($item * 60);
                    }
                    if($average > $limit){
                        $this->handleViolation("A: ".$average." L: ".$limit);
                    }
                    $delays->clear();
                }              
                $this->setLastPacketTime($profile->getName(), microtime(true));
            }elseif($onGround){
                $delays->clear();  
            }
        }
    }

    private function getLastPacketTime(string $profileName) : int|float{
        return self::$lastPacketTime[$profileName];
    }

    private function getLinkedList(string $profileName) : LinkedList{
        return self::$linkedList[$profileName];
    }

    private function setLastPacketTime(string $profileName, int|float $time) : void{
        self::$lastPacketTime[$profileName] = $time;
    }

    private function setLinkedList(string $profileName) : void{
        self::$linkedList[$profileName] = new LinkedList();
    }

    private function isHaveLastPacketTime(string $profileName) :bool{
        if(!empty(self::$lastPacketTime[$profileName])){
            return true;
        }
        return false;
    }

    private function isHaveLinkedList(string $profileName) :bool{
        if(!empty(self::$linkedList[$profileName])){
            return true;
        }
        return false;
    }
}