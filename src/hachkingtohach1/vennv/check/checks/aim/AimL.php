<?php

namespace hachkingtohach1\vennv\check\checks\aim;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInUseEntity;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class AimL extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayInUseEntity) return;   
        
        $this->checkInfo(
            self::ATTACK, "L", "Aim", 3, $origin
        );

        $profile = $this->getProfile();

        $teleportTicks = $profile->getTeleportTicks();

        if($teleportTicks < 3){
            return;
        }

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(20);

        $deltaX = $profile->getDeltaX();
        $deltaY = $profile->getDeltaY();

        $deltaYaw = $profile->getDeltaYaw();
        $lastDeltaYaw = $profile->getLastDeltaYaw();

        $deltaPitch = $profile->getDeltaPitch();
        $lastDeltaPitch = $profile->getLastDeltaPitch();

        $yawAccel = abs($deltaYaw - $lastDeltaYaw);
        $pitchAccel = abs($deltaPitch - $lastDeltaPitch);

        if(
            $yawAccel < 1E-3 && $pitchAccel < 1E-4
            && ($deltaPitch > 0 || $yawAccel > 0)
            && ($deltaX > 2 || $deltaY > 2)
        ){
            $fakeMapViolation->addViolation(1);
            if($fakeMapViolation->getViolations() >= $fakeMapViolation->getMaxViolation()){
                $this->handleViolation("DY: ".$deltaYaw." LDY: ".$lastDeltaYaw." DP: ".$deltaPitch." LDP: ".$lastDeltaPitch);
            }else{
                $this->addViolation(-0.001);
                $fakeMapViolation->addViolation(-1);
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