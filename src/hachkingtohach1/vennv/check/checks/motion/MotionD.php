<?php

namespace hachkingtohach1\vennv\check\checks\motion;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityEffect;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\Cuboid;
use hachkingtohach1\vennv\utils\MoveUtils;

class MotionD extends PacketCheck{

    private static array $lastLocation = [];

    public function handle(VPacket $packet, string $origin) : void{   
        if(!$packet instanceof VPacketPlayOutPosition) return;
        
        $this->checkInfo(
            self::MOVE, "D", "Motion", 3, $origin
        );

        $profile = $this->getProfile();

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        $onElastomers = $profile->isOnElastomers();

        $location = $profile->getLocation();

        $joinTicks = $profile->getJoinTicks();
        $deathTicks = $profile->getDeathTicks();
        $respawnTicks = $profile->getRespawnTicks();
        $elastomersTicks = $profile->getElastomersTicks();

        if($joinTicks > 3 && $elastomersTicks > 2 && (($deathTicks > $respawnTicks) || ($deathTicks > 3 && $respawnTicks > 3))){
            if($packet->onGround){
                if(isset(self::$lastLocation[$profile->getName()])){
                    $lastLocation = self::$lastLocation[$profile->getName()];
                    $cuboid = new Cuboid();
                    $cuboid->set(
                        $lastLocation->getX(), $lastLocation->getY(), $lastLocation->getZ(), $lastLocation->getYaw(), $lastLocation->getPitch(),
                        $location->getX(), $location->getY(), $location->getZ(), $location->getYaw(), $location->getPitch()
                    );

                    $limit = MoveUtils::DISTACE_JUMP;

                    $limit += abs($lastLocation->getY() - $location->getY())/0.5;

                    $limit += $onElastomers ? 0.3 : 0;

                    $effects = $profile->getEffectHandler();

                    foreach($effects->getEffects() as $effect => $data){
                        if($effect === VPacketPlayOutEntityEffect::JUMP){
                            $limit += $data["amplifier"] * 0.75;
                        }
                    }

                    if($cuboid->getReach() > $limit){
                        $this->handleViolation("D: ".$cuboid->getReach()." L: ".$limit);
                    }else{
                        $this->addViolation(-0.01);
                    }
                    unset(self::$lastLocation[$profile->getName()]);
                }else{
                    self::$lastLocation[$profile->getName()] = $location;
                }
            }
        }
    }
}