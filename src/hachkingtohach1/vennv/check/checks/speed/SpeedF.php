<?php

namespace hachkingtohach1\vennv\check\checks\speed;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketSentFrequently;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\Cuboid;
use hachkingtohach1\vennv\utils\php\LinkedList;

class SpeedF extends PacketCheck{

    private static array $lastTime = [];
    private static array $lastLocation = [];
    private static array $linkedList = [];

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketSentFrequently) return;
        
        $this->checkInfo(
            self::MOVE, "F", "Speed", 5, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveLinkedList($profile->getName())){
            $this->setLinkedList($profile->getName());
        }

        $delays = $this->getLinkedList($profile->getName());

        $ping = $profile->getPing();

        $onGround = $profile->getOnGround();
        $onIce = $profile->isOnIce();

        $location = $profile->getLocation();

        $joinTicks = $profile->getJoinTicks();
        $moveTicks = $profile->getMoveTicks();

        $deathTicks = $profile->getDeathTicks();
        $respawnTicks = $profile->getRespawnTicks();

        if($joinTicks > 3 && $onGround){
            if($deathTicks > 3 && $respawnTicks > 3){

                if(!isset(self::$lastLocation[$profile->getName()])){
                    self::$lastLocation[$profile->getName()] = $location;
                }

                if(!isset(self::$lastTime[$profile->getName()])){
                    self::$lastTime[$profile->getName()] = microtime(true);
                }

                if(microtime(true) - self::$lastTime[$profile->getName()] > 0.5){

                    $lastLocation = self::$lastLocation[$profile->getName()];

                    $cuboid = new Cuboid();
                    $cuboid->set(
                        $location->getX(), $location->getY(), $location->getZ(), $location->getYaw(), $location->getPitch(), 
                        $lastLocation->getX(), $lastLocation->getY(), $lastLocation->getZ(), $lastLocation->getYaw(), $lastLocation->getPitch()
                    );

                    $distance = ($cuboid->getReach() * 2) - ($moveTicks * 2) - ($profile->getSpeed() * 20) - ($ping / 300);
                    
                    if($onIce){
                        $distance -= 3.5;
                    }
                    
                    $delays->add($distance);               
                    if($delays->size() >= 10){
                        $average = 0;
                        foreach($delays->toArrayFirst() as $item){
                            $average += $item;
                        }
                        if($average > 70){
                            $this->handleViolation("A: ".$average);
                        }else{
                            $this->addViolation(-1);
                        }
                        $delays->clear();
                    }              

                    self::$lastTime[$profile->getName()] = microtime(true);
                    self::$lastLocation[$profile->getName()] = $location;
                }
            }else{
                unset(self::$lastTime[$profile->getName()]);
                unset(self::$lastLocation[$profile->getName()]);
            }
        }
    }

    private function getLinkedList(string $profileName) : LinkedList{
        return self::$linkedList[$profileName];
    }

    private function setLinkedList(string $profileName) : void{
        self::$linkedList[$profileName] = new LinkedList();
    }

    private function isHaveLinkedList(string $profileName) :bool{
        if(!empty(self::$linkedList[$profileName])){
            return true;
        }
        return false;
    }
}