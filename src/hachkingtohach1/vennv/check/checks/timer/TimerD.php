<?php

namespace hachkingtohach1\vennv\check\checks\timer;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketSentFrequently;
use hachkingtohach1\vennv\compat\VPacket;

class TimerD extends PacketCheck{

    private static array $lastSent = [];

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketSentFrequently) return;
        
        $this->checkInfo(
            self::MISC, "D", "Timer", 5, $origin
        );

        $profile = $this->getProfile();

        $hasJoined = $profile->getHasJoined();

        $joinTicks = $profile->getJoinTicks();
        $moveTicks = $profile->getMoveTicks();

        $limit = 9.5367431640625E-7 + $moveTicks;

        if($joinTicks > 3 && $hasJoined){   
            if(!isset(self::$lastSent[$profile->getName()])){
                self::$lastSent[$profile->getName()] = microtime(true);
                return;
            }
            $time = (microtime(true) - self::$lastSent[$profile->getName()]) + $moveTicks;
            if($time <= $limit){
                $this->handleViolation("T: $time, L: $limit");
            }else{
                $this->addViolation(-1);
            }
            self::$lastSent[$profile->getName()] = microtime(true);
        }else{
            unset(self::$lastSent[$profile->getName()]);
        }
    }
}