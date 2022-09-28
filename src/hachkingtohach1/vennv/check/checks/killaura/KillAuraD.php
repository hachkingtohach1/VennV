<?php

namespace hachkingtohach1\vennv\check\checks\killaura;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockDig;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInUseEntity;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class KillAuraD extends PacketCheck{

    private static array $fakeMapViolation = [];
    private static array $attack = [];

    public function handle(VPacket $packet, string $origin) : void{
        
        $this->checkInfo(
            self::ATTACK, "D", "KillAura", 3, $origin
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

        if($packet instanceof VPacketPlayInUseEntity){
            if($packet->action === VPacketPlayInUseEntity::ACTION_ATTACK){
                self::$attack[$profile->getName()] = microtime(true);
            }
        }

        if($packet instanceof VPacketPlayInBlockDig){
            if(isset(self::$attack[$profile->getName()])){
                $attack = self::$attack[$profile->getName()];
                $diff = microtime(true) - $attack;
                if($diff < 0.1){
                    $this->handleViolation("D: ".$diff);
                }
                if($fakeMapViolation->handleViolation()){
                    $this->handleViolation("D: ".$diff." M: ".microtime(true));
                }
                unset(self::$attack[$profile->getName()]);
            }
        }
    }

    private function getFakeMapViolation(string $profileName) : FakeMapViolation{
        return self::$fakeMapViolation[$profileName];
    }

    private function setFakeMapViolation(string $profileName) : void{
        self::$fakeMapViolation[$profileName] = new FakeMapViolation();
    }

    private function isHaveFakeMapViolation(string $profileName) :bool{
        return isset(self::$fakeMapViolation[$profileName]);
    }
}