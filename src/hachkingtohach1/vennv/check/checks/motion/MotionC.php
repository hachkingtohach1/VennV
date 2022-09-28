<?php

namespace hachkingtohach1\vennv\check\checks\motion;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityEffect;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\MoveUtils;

class MotionC extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayOutPosition) return;
        
        $this->checkInfo(
            self::MOVE, "C", "Motion", 3, $origin
        );

        $profile = $this->getProfile();

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        $onElastomers = $profile->isOnElastomers();

        $onStair = $profile->isOnStair();

        $deltaY = $profile->getDeltaY();

        $joinTicks = $profile->getJoinTicks();
        $deathTicks = $profile->getDeathTicks();
        $respawnTicks = $profile->getRespawnTicks();
        $elastomersTicks = $profile->getElastomersTicks();

        $effects = $profile->getEffectHandler();

        $add = 0;
        foreach($effects->getEffects() as $effect => $data){
            if($effect === VPacketPlayOutEntityEffect::JUMP){
                $add += $data["amplifier"] * 0.5;
            }
        }

        $limit = MoveUtils::JUMP_MOTION + $add;

        $limit += $onStair ? 0.7000000000000028 - MoveUtils::JUMP_MOTION : 0;

        $limit += $onElastomers ? 0.3 : 0;

        if($deltaY > $limit && $elastomersTicks > 2 && $joinTicks > 3 && $deathTicks > 3 && $respawnTicks > 3){
            $this->handleViolation("D: ".$deltaY." L: ".$limit);
        }else{
            $this->addViolation(-0.02);
        }
    }
}