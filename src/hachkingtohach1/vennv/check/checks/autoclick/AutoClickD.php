<?php

namespace hachkingtohach1\vennv\check\checks\autoclick;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInArmAnimation;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\packets\VPlayerActionPacket;
use hachkingtohach1\vennv\compat\VPacket;

class AutoClickD extends PacketCheck{

    private static array $swings = [];
    private static array $lastSwing = [];
    private static array $movements = [];

    public function handle(VPacket $packet, string $origin) : void{ 
        
        $this->checkInfo(
            self::INTERACT, "D", "AutoClick", 5, $origin
        );

        $profile = $this->getProfile();

        if(!isset(self::$swings[$profile->getName()])){
            self::$swings[$profile->getName()] = 0;
        }

        if(!isset(self::$lastSwing[$profile->getName()])){
            self::$lastSwing[$profile->getName()] = 0;
        }

        if(!isset(self::$movements[$profile->getName()])){
            self::$movements[$profile->getName()] = 0;
        }

        $moveTicks = $profile->getMoveTicks();

        $diggingTicks = $profile->getDigBlockTicks();
        $placingTicks = $profile->getPlaceBlockTicks();
        $attackTicks = $profile->getAttackTicks();

        if($packet instanceof VPlayerActionPacket){
            if(
                $packet->action === VPlayerActionPacket::START_BREAK ||
                $packet->action === VPlayerActionPacket::ABORT_BREAK
            ){
                self::$movements[$profile->getName()] = 0;
            }
        }

        if($diggingTicks > 2 && $placingTicks > 1 && $attackTicks < 2){
            if($packet instanceof VPacketPlayInArmAnimation){
                if($moveTicks < 1.83){
                    self::$swings[$profile->getName()]++;
                    self::$lastSwing[$profile->getName()] = microtime(true);
                }
            }

            if($packet instanceof VPacketPlayOutPosition){
                if(self::$swings[$profile->getName()] > 0){
                    self::$movements[$profile->getName()]++;
                    if(self::$movements[$profile->getName()] >= 20){
                        if(self::$swings[$profile->getName()] > 20){
                            $this->handleViolation("S: ".self::$swings[$profile->getName()]);
                        }else{
                            $this->addViolation(-0.1);
                        }
                        self::$swings[$profile->getName()] = 0;
                        self::$lastSwing[$profile->getName()] = 0;
                        self::$movements[$profile->getName()] = 0;
                    }
                }
            }
        }
    }
}