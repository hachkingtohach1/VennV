<?php

namespace hachkingtohach1\vennv\check\checks\reach;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInAttackEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityEffect;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\Cuboid;
use hachkingtohach1\vennv\utils\MoveUtils;

class ReachE extends PacketCheck{

    private static array $lastX = [];
    private static array $lastY = [];

    public function handle(VPacket $packet, string $origin) : void{ 
        if(!$packet instanceof VPacketPlayInAttackEntity) return;
        
        $this->checkInfo(
            self::ATTACK, "E", "Reach", 5, $origin
        );

        $profile = $this->getProfile();

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        if(!isset(self::$lastY[$profile->getName()])){
            self::$lastY[$profile->getName()] = $packet->targetY;
        }

        if(!isset(self::$lastX[$profile->getName()])){
            self::$lastX[$profile->getName()] = $packet->targetX;
        }

        $speed = $profile->getSpeed();

        $onLiquid = $profile->isOnLiquid();

        $ping = $profile->getPing();

        $moveTicks = $profile->getMoveTicks();

        $cuboid = new Cuboid();
        $cuboid->set(
            $packet->targetX, $packet->targetY, $packet->targetZ, 0, 0,
            $packet->originX, $packet->originY, $packet->originZ, 0, 0
        );

        $reach = $cuboid->getReach();

        if($reach > 7) return;

        $maxReach = 3.55;
		$yawDiff = abs(180 - abs($packet->originYaw - $packet->targetYaw));
		$yDiff = abs($packet->originY - $packet->targetY);

        if($speed > 0.1){
            $maxReach += 0.45;
        }

        if($onLiquid){
            $maxReach += 0.25;
        }

        if($yDiff > 5){
			return;
		}

        $effects = $profile->getEffectHandler();

        foreach($effects->getEffects() as $effect => $data){
            if($effect === VPacketPlayOutEntityEffect::JUMP){
                $maxReach += 0.40 * ($data["amplifier"] + 1);
            }
        }

        $deltaX = $packet->targetX - self::$lastX[$profile->getName()];
        $deltaY = $packet->targetY - self::$lastY[$profile->getName()];

        $velocityX = MoveUtils::FRICTION * ($deltaX) - 0.08;
        $velocityY = MoveUtils::MOTION_Y_FRICTION * ($deltaY) - 0.08;

        $maxReach += $ping * 0.008;
		$maxReach += (($yawDiff > 100 && $velocityY < 0.2) ? ($yawDiff * 0.011) : ($yawDiff * 0.001));
		$maxReach += $yDiff / 0.7;
		$maxReach += abs($deltaY) * 2;
		$maxReach += $velocityY * 0.75;
		$maxReach += $velocityX * 0.15;
		$maxReach += (($speed <= 0.2) ? 0.0 : ($speed - 0.2));

        if($reach > $maxReach){
        	$this->handleViolation("R: ".$reach." M: ".$maxReach." YD: ".$yDiff." Y: ".$packet->targetY." LY: ".self::$lastY[$profile->getName()]." VY: ".$velocityY." V: ".$speed." P: ".$ping." YD: ".$yawDiff." V: ".$velocityX." M: ".$moveTicks." GM: ".$gameMode);
        }else{
            $this->addViolation(-0.05);
        }

        self::$lastY[$profile->getName()] = $packet->targetY;
        self::$lastX[$profile->getName()] = $packet->targetX;
    }
}