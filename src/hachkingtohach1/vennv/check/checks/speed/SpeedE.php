<?php

namespace hachkingtohach1\vennv\check\checks\speed;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;

class SpeedE extends PacketCheck{

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayOutPosition) return;
        
        $this->checkInfo(
            self::MOVE, "E", "Speed", 5, $origin
        );

        $profile = $this->getProfile();

        if($profile->getPlacingBlock()){
            $profile->setPlacingBlock(false);
            return;
        }

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        $joinTicks = $profile->getJoinTicks();

        $onGround = $profile->getOnGround();
        $onLiquid = $profile->isOnLiquid();

        $speed = $profile->getSpeed();

        $deltaY = $profile->getDeltaY();
        $lastDeltaY = $profile->getLastDeltaY();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();
        $lastLastLocation = $profile->getLastLastLocation();

        $accel = abs($deltaY - $lastDeltaY);

        $boubt = 0;

        if($speed > 0.13){
            $boubt -= $speed * 0.00008;
        }

        if(!$onLiquid && !$onGround && !$location->isOnGround() && $lastLocation->isOnGround() && $lastLastLocation->isOnGround() && $joinTicks > 2){
            if($accel < $boubt){              
                $this->handleViolation("A: ".$accel." B: ".$boubt);
            }
        }
    }
}