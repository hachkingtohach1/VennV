<?php

namespace hachkingtohach1\vennv\check\checks\scaffold;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockPlace;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\BlockPosition;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class ScaffoldA extends PacketCheck{

    private static array $lastYaw = [];
    private static array $lastBlockPosition = [];
    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayInBlockPlace) return;
        
        $this->checkInfo(
            self::INTERACT, "A", "Scaffold", 1, $origin
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
        $fakeMapViolation->setMaxViolation(3);
        $fakeMapViolation->setMaxTicks(0.5);

        $yaw = $profile->getLocation()->getYaw();

        $x = $packet->x;
        $y = $packet->y;
        $z = $packet->z;
        $blockPosition = new BlockPosition();
        $blockPosition->set($x, $y, $z);

        if($this->isHaveLastBlockPosition($profile->getName()) && $this->isHaveLastYaw($profile->getName())){
            $lastBlockPosition = $this->getLastBlockPosition($profile->getName());
            $ly = $lastBlockPosition->getY();
            if($blockPosition->nearby($lastBlockPosition) && $y == $ly){
                $lastYaw = $this->getLastYaw($profile->getName());
                $abs = abs($yaw - $lastYaw);
                if($abs > 20){
                    if($fakeMapViolation->handleViolation()){
                        $this->handleViolation("A: ".$abs);
                    }
                }
            }
        }
        
        $this->setLastBlockPosition($profile->getName(), $blockPosition);
        $this->setLastYaw($profile->getName(), $yaw);
    }

    public function isHaveLastYaw(string $profileName) : bool{
        return !empty(self::$lastYaw[$profileName]);
    }

    public function setLastYaw(string $profileName, int|float $yaw) : void{
        self::$lastYaw[$profileName] = $yaw;
    }

    public function getLastYaw(string $profileName) : int|float{
        return self::$lastYaw[$profileName];
    }

    public function isHaveLastBlockPosition(string $profileName) : bool{
        return !empty(self::$lastBlockPosition[$profileName]);
    }

    public function setLastBlockPosition(string $profileName, BlockPosition $blockPosition) : void{
        self::$lastBlockPosition[$profileName] = $blockPosition;
    }

    public function getLastBlockPosition(string $profileName) : BlockPosition{
        return self::$lastBlockPosition[$profileName];
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