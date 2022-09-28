<?php

namespace hachkingtohach1\vennv\check\checks\killaura;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInArmAnimation;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInUseEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;

class KillAuraC extends PacketCheck{

    private static array $attack = [];
    private static array $arm = [];
    private static array $lastFlying = [];

    public function handle(VPacket $packet, string $origin) : void{
        
        $this->checkInfo(
            self::ATTACK, "C", "KillAura", 5, $origin
        );

        $profile = $this->getProfile();

        $ping = $profile->getPing();

        $moveTicks = $profile->getMoveTicks();

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        if($packet instanceof VPacketPlayInUseEntity){
            if($packet->action === VPacketPlayInUseEntity::ACTION_ATTACK){
                self::$attack[$profile->getName()] = microtime(true);
            }
        }

        if($packet instanceof VPacketPlayOutPosition){
            if(
                isset(self::$attack[$profile->getName()]) &&
                isset(self::$arm[$profile->getName()]) &&
                isset(self::$lastFlying[$profile->getName()])
            ){
                $abs = abs(self::$attack[$profile->getName()] - self::$arm[$profile->getName()]);
                if($abs <= 0.2){
                    $abs2 = microtime(true) - self::$attack[$profile->getName()];
                    if($abs2 > 0.9 + ($moveTicks * 2) + ($ping / 300) || $abs2 < 0.0001){
                        $this->handleViolation("A: ".$abs." A2: ".$abs2);
                    }else{
                        $this->addViolation(-0.05);
                    }
                }
            }
            self::$lastFlying[$profile->getName()] = microtime(true);
            unset(self::$attack[$profile->getName()]);
            unset(self::$arm[$profile->getName()]);
        }

        if($packet instanceof VPacketPlayInArmAnimation){
            self::$arm[$profile->getName()] = microtime(true);
        }
    }
}