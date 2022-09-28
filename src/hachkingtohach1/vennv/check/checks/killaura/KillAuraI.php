<?php

namespace hachkingtohach1\vennv\check\checks\killaura;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInAttackEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class KillAuraI extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayInAttackEntity) return;
        
        $this->checkInfo(
            self::ATTACK, "I", "KillAura", 3, $origin
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
        $fakeMapViolation->setMaxTicks(0.4);

        $deltaX = $profile->getDeltaX();
        $deltaZ = $profile->getDeltaZ();
        $deltaXZ = $profile->getDeltaXZ();

        $deltaX *= 0.6;
        $deltaZ *= 0.6;

        $pxz = ($deltaX * $deltaX + $deltaZ * $deltaZ);
        $noxz = pow($deltaXZ, 2);
        $deltaXZ = pow($deltaXZ, 2);

        $deltaYes = abs($deltaXZ - $pxz);
        $deltaNo = abs($deltaXZ - $noxz);

        if($deltaYes > 0.059 && $deltaNo < 0.0001){
            if($fakeMapViolation->handleViolation()){
                $this->handleViolation("Y: ".$deltaYes." N: ".$deltaNo);
            }
        }else{
            $fakeMapViolation->addViolation(-0.01);
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