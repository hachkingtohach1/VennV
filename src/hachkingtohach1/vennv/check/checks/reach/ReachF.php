<?php

namespace hachkingtohach1\vennv\check\checks\reach;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInAttackEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityEffect;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\Cuboid;
use hachkingtohach1\vennv\utils\SampleList;

class ReachF extends PacketCheck{

    private static array $sampleList = [];

    public function handle(VPacket $packet, string $origin) : void{ 
        if(!$packet instanceof VPacketPlayInAttackEntity) return;
        
        $this->checkInfo(
            self::ATTACK, "F", "Reach", 5, $origin
        );

        $profile = $this->getProfile();

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        if(!$this->isHaveSampleList($profile->getName())){
            $this->setSampleList($profile->getName());
        }

        $sampleList = $this->getSampleList($profile->getName());
        $sampleList->setMaxSample(45);

        $onGround = $profile->getOnGround();

        $ping = $profile->getPing();

		$yawDiff = abs(180 - abs($packet->originYaw - $packet->targetYaw));
		$yDiff = abs($packet->originY + 0.2 - $packet->targetY + 0.2);
        $yDist = $yDiff > 0.5 ? $yDiff : 0;

        $cuboid = new Cuboid();
        $cuboid->set(
            $packet->targetX, $packet->targetY + 0.2, $packet->targetZ, 0, 0,
            $packet->originX, $packet->originY + 0.2, $packet->originZ, 0, 0
        );

        $reach = $cuboid->getReach() + 0.32;

        if($reach > 6.5) return;

        if(!$onGround){
            $reach -= 0.1;
        }

        $reach -= $yawDiff > 100 && $yDist < 0.1 ? $yawDiff * 0.005 : $yawDiff * 0.0025;
        $reach -= $yDist * .25;

        $effects = $profile->getEffectHandler();

        foreach($effects->getEffects() as $effect => $data){
            if($effect === VPacketPlayOutEntityEffect::JUMP){
                $reach -= 0.06 * ($data["amplifier"] + 1);
            }
        }

        $result = $sampleList->handleSample($reach);
        if(count($result) >= $sampleList->getMaxSample()){
            $sum = 0;
            foreach($result as $sample){
                $sum += $sample;
            }
            $average = $sum / count($result);
            if($average > 3.001){
                $this->handleViolation("A: ".$average." R: ".$reach." YD: ".$yawDiff." Y: ".$yDist." P: ".$ping." GM: ".$gameMode." OG: ".$onGround);
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