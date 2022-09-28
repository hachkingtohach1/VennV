<?php

namespace hachkingtohach1\vennv\check\checks\reach;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInAttackEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityEffect;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\Cuboid;
use hachkingtohach1\vennv\utils\MoveUtils;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class ReachD extends PacketCheck{

    private static array $lastX = [];
    private static array $lastY = [];
    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{ 
        if(!$packet instanceof VPacketPlayInAttackEntity) return;
        
        $this->checkInfo(
            self::ATTACK, "D", "Reach", 5, $origin
        );

        $profile = $this->getProfile();

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());

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

        $maxReach = 3.1;
		$yawDiff = abs(180 - abs($packet->originYaw - $packet->targetYaw));
		$yDiff = abs($packet->originY - $packet->targetY);

        if($speed > 0.1){
            $maxReach += 0.52;
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

        $maxReach += ($ping + $ping) / 2 * 0.005;
		$maxReach += (($yawDiff > 100.0 && $velocityY < 0.2) ? ($yawDiff * 0.011) : ($yawDiff * 0.001));
		$maxReach += $yDiff / 0.25;
		$maxReach += abs($deltaY) * 2.8;
		$maxReach += $velocityY * 0.9;
		$maxReach += $velocityX * 0.34;
		$maxReach += (($speed <= 0.2) ? 0.0 : ($speed - 0.2));

        if($reach > $maxReach + 1.25){
            $fakeMapViolation->addViolation(3);
        }elseif($reach > $maxReach){
        	$fakeMapViolation->addViolation(1);
        }else{
            $this->addViolation(-0.1);
        }

        if($fakeMapViolation->getViolations() > 5 && $reach > $maxReach){
			$this->handleViolation("R: ".$reach." M: ".$maxReach." YD: ".$yDiff." Y: ".$packet->targetY." LY: ".self::$lastY[$profile->getName()]." VY: ".$velocityY." V: ".$speed." P: ".$ping." YD: ".$yawDiff." V: ".$velocityX." M: ".$moveTicks." GM: ".$gameMode);
            $fakeMapViolation->setViolation(0);
        }

        self::$lastY[$profile->getName()] = $packet->targetY;
        self::$lastX[$profile->getName()] = $packet->targetX;
    }

    private function getFakeMapViolation(string $profileName) : FakeMapViolation{
        return self::$fakeMapViolation[$profileName];
    }

    private function setFakeMapViolation(string $profileName) : void{
        self::$fakeMapViolation[$profileName] = new FakeMapViolation();
    }

    private function isHaveFakeMapViolation(string $profileName) :bool{
        return !empty(self::$fakeMapViolation[$profileName]);
    }
}