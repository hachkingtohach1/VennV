<?php

namespace hachkingtohach1\vennv\check\checks\autoclick;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockDig;
use hachkingtohach1\vennv\compat\packets\VPlayerActionPacket;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\MathUtil;
use hachkingtohach1\vennv\utils\SampleList;
use hachkingtohach1\vennv\utils\Vector;

class AutoClickE extends PacketCheck{

    private static array $blockDigging = [];
    private static array $abortBreak = [];
    private static array $ticks = [];
    private static array $sampleList = [];

    public function handle(VPacket $packet, string $origin) : void{ 
        
        $this->checkInfo(
            self::INTERACT, "E", "AutoClick", 5, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveSampleList($profile->getName())){
            $this->setSampleList($profile->getName());
        }

        $sampleList = $this->getSampleList($profile->getName());
        $sampleList->setMaxSample(41);

        $diggingTicks = $profile->getDigBlockTicks();

        if(!isset(self::$ticks[$profile->getName()])){
            self::$ticks[$profile->getName()] = 0;
        }

        if($packet instanceof VPacketPlayInBlockDig){
            $vectorBlock = new Vector();
            $vectorBlock->set($packet->x, $packet->y, $packet->z);
            self::$blockDigging[$profile->getName()] = $vectorBlock;
        }

        if($packet instanceof VPlayerActionPacket){
            if($packet->action === VPlayerActionPacket::ABORT_BREAK){
                self::$abortBreak[$profile->getName()] = true;
            }
            if($diggingTicks < 1 && !isset(self::$abortBreak[$profile->getName()])){
                self::$ticks[$profile->getName()]++;
            }else{
                unset(self::$abortBreak[$profile->getName()]);
            }
            if(isset(self::$blockDigging[$profile->getName()])){
                if($packet->action === VPlayerActionPacket::START_BREAK){
                    self::$ticks[$profile->getName()] = 0;
                }elseif($packet->action === VPlayerActionPacket::ABORT_BREAK){
                    $vectorBlock = new Vector();
                    $vectorBlock->set($packet->x, $packet->y, $packet->z);
                    if($vectorBlock->equals(self::$blockDigging[$profile->getName()])){
                        $result = $sampleList->handleSample(self::$ticks[$profile->getName()]);
                        if(count($result) >= $sampleList->getMaxSample()){
                            $deviation = MathUtil::getDeviation($result);
                            if($deviation < 0.325){
                                $this->handleViolation("D: ".$deviation);
                            }else{
                                $this->addViolation(-0.25);
                            }
                        }
                    }
                }
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