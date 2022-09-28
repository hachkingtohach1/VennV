<?php

namespace hachkingtohach1\vennv\check\checks\timer;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketSentFrequently;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\data\manager\DataManager;
use hachkingtohach1\vennv\utils\RequiredPoints;

class TimerC extends PacketCheck{

    private static array $requiredPoints = [];

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketSentFrequently) return;
        
        $this->checkInfo(
            self::MISC, "C", "Timer", 5, $origin
        );

        $profile = $this->getProfile();

        $hasJoined = $profile->getHasJoined();

        $joinTicks = $profile->getJoinTicks();

        $deathTicks = $profile->getDeathTicks();
        $respawnTicks = $profile->getRespawnTicks();

        if($joinTicks > 3 && $hasJoined){   
            if(!$this->isHaverequiredPoints($profile->getName())){
                $this->setrequiredPoints($profile->getName());
            }

            $limit = [3 => 200, 4 => 200, 5 => 200];

            $requiredPoints = $this->getRequiredPoints($profile->getName());
            $requiredPoints->setMax($limit[DataManager::getAPIServer()]);
            $requiredPoints->setMaxTicks(1);    
                
            if($deathTicks > 3 && $respawnTicks > 3){               
                $point = $requiredPoints->getPoint();        
                if($requiredPoints->getTicks() >= $requiredPoints->getMaxTicks()){
                    if($point >= $requiredPoints->getMax() && $joinTicks > 5){
                        $this->handleViolation("P: ".$requiredPoints->getPoint());
                    }else{
                        $this->addViolation(-0.5);
                    }
                    $requiredPoints->resetTicks();
                    $requiredPoints->resetPoint();
                }
                $requiredPoints->addPoint(1);           
            }
        }
    }

    private function getRequiredPoints(string $profileName) : RequiredPoints{
        return self::$requiredPoints[$profileName];
    }

    private function setRequiredPoints(string $profileName) : void{
        self::$requiredPoints[$profileName] = new RequiredPoints();
    }

    private function isHaveRequiredPoints(string $profileName) :bool{
        if(!empty(self::$requiredPoints[$profileName])){
            return true;
        }
        return false;
    }
}