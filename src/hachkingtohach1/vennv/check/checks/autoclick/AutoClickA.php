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

class AutoClickA extends PacketCheck{

    private static array $swing = [];
    private static array $place = [];
    private static array $digging = [];
    private static array $linkedList = [];
    private static array $tick = [];

    public function handle(VPacket $packet, string $origin) : void{     
        
        $this->checkInfo(
            self::INTERACT, "A", "AutoClick", 10, $origin
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
                        if($linkedList->size() > 40){
                            $deviation = MathUtil::getDeviation($linkedList->toArrayFirst());
                            $n = (0.325 - $deviation) * 2.0 + 0.675;
                            if($n > -2){                               
                                $this->handleViolation("D: ".$deviation." N: ".$n);
                            }else{
                                $this->addViolation(-$n);
                            }
                            $linkedList->clear();
                        }
                    }
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