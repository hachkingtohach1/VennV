<?php

namespace hachkingtohach1\vennv\check\checks\velocity;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutUnderAttack;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;
use hachkingtohach1\vennv\utils\MathUtil;

class VelocityH extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutUnderAttack) return;
        
        $this->checkInfo(
            self::MOVE, "H", "Velocity", 1, $origin
        );

        $profile = $this->getProfile();

        $onLiquid = $profile->isOnLiquid();
        $onWeb = $profile->isOnWeb();

        if($onLiquid || $onWeb){
            return;
        }

        $teleportTicks = $profile->getTeleportTicks();

        if($teleportTicks < 5){
            return;
        }

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());

        $velocityY = $profile->getVelocityY();

        $onGround = $profile->getOnGround();

        $ping = $profile->getPing();

        if($velocityY > 0 && !$onGround){
            $threshold = 20 + (MathUtil::pingFormula($ping) * 2);
            if($fakeMapViolation->getViolations() >= $threshold){
                $this->handleViolation("Y: ".$velocityY." T: ".$threshold);
                $fakeMapViolation->setViolation(0);
            }                
            $fakeMapViolation->addViolation(1);
        }else{
            $fakeMapViolation->setViolation(0);
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