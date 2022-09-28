<?php

namespace hachkingtohach1\vennv\check\checks\autoclick;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInArmAnimation;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockDig;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockPlace;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\MathUtil;
use hachkingtohach1\vennv\utils\php\LinkedList;

class AutoClickB extends PacketCheck{

    private static array $swing = [];
    private static array $place = [];
    private static array $digging = [];
    private static array $linkedList = [];
    private static array $tick = [];
    private static array $lastTick = [];
    private static array $done = [];

    public function handle(VPacket $packet, string $origin) : void{     
        
        $this->checkInfo(
            self::INTERACT, "B", "AutoClick", 10, $origin
        );

        $profile = $this->getProfile();

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        if($packet instanceof VPacketPlayInArmAnimation){
            self::$swing[$profile->getName()] = microtime(true);
        }

        if($packet instanceof VPacketPlayInBlockPlace){
            self::$place[$profile->getName()] = microtime(true);
        }

        if($packet instanceof VPacketPlayInBlockDig){
            self::$digging[$profile->getName()] = microtime(true);
        }

        if($packet instanceof VPacketPlayOutPosition){
            if(!isset(self::$linkedList[$profile->getName()])){
                self::$linkedList[$profile->getName()] = new LinkedList();
            }
            if(!isset(self::$tick[$profile->getName()])){
                self::$tick[$profile->getName()] = 0;
            }
            $linkedList = self::$linkedList[$profile->getName()];
            if(!$packet->onGround){
                if(
                    isset(self::$swing[$profile->getName()]) &&
                    !isset(self::$place[$profile->getName()]) &&
                    !isset(self::$place[$profile->getName()]) &&
                    !isset(self::$digging[$profile->getName()]) &&
                    $profile->getAttackTicks() < 1.2
                ){
                    if(self::$tick[$profile->getName()] < 8){
                        $linkedList->add(self::$tick[$profile->getName()]);                       
                    }
                    if($linkedList->size() > 40){
                        $average = MathUtil::getAverage($linkedList->toArrayFirst());
                        if($average < 2.5){
                            $ticks = self::$tick[$profile->getName()];
                            $lastTicks = self::$lastTick[$profile->getName()];
                            if($ticks > 3 && $ticks < 20 && $lastTicks < 20){
                                $this->addViolation(-min($this->getViolations(), 0.25));
                                self::$done[$profile->getName()] = 0;
                            }else{
                                if(!isset(self::$done[$profile->getName()])){
                                    self::$done[$profile->getName()] = 0;
                                }
                                $done = self::$done[$profile->getName()];
                                self::$done[$profile->getName()] = $done + 1;
                                if($done > 600.0 / ($average * 1.5)){
                                    $this->handleViolation("A: ".$average);
                                    self::$done[$profile->getName()] = 0;                                  
                                }
                            }
                        }else{
                            self::$done[$profile->getName()] = 0; 
                        }
                        $linkedList->clear();
                    }
                    self::$lastTick[$profile->getName()] = self::$tick[$profile->getName()];
                    self::$tick[$profile->getName()] = 0;
                }
                unset(self::$swing[$profile->getName()]);
                unset(self::$place[$profile->getName()]);
                unset(self::$digging[$profile->getName()]);
                self::$tick[$profile->getName()]++;
            }
        }
    }

    public function isHaveLinkedList(string $profileName) : bool{
        return isset(self::$linkedList[$profileName]);
    }
}