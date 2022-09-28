<?php

namespace hachkingtohach1\vennv\check\checks\killaura;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInAttackEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\Location;
use hachkingtohach1\vennv\utils\MathUtil;
use hachkingtohach1\vennv\utils\Vector;
use hachkingtohach1\vennv\utils\SampleList;

class KillAuraG extends PacketCheck{

    private static array $sampleList = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayInAttackEntity) return;
        
        $this->checkInfo(
            self::ATTACK, "G", "KillAura", 3, $origin
        );

        $profile = $this->getProfile();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        if(!$this->isHaveSampleList($profile->getName())){
            $this->setSampleList($profile->getName());
        }

        $sampleList = $this->getSampleList($profile->getName());
        $sampleList->setMaxSample(5);

        if(min(abs($location->getYaw() - $lastLocation->getYaw()), abs($location->getPitch() - $lastLocation->getPitch())) > 0.5){
            
        }
    }

    private static function handleA(int|float $n, int|float $n2) : int|float{
        return abs($n2 - $n) + abs($n2 / 2.0);
    }
    
    private static function handleB(Location $location, Vector $vector) : int|float{
        return MathUtil::getLuckyAura($location, $vector);
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