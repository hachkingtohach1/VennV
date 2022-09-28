<?php

namespace hachkingtohach1\vennv\check\checks\aim;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class AimG extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function getCloning() : int{
        return 2;
    }

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayOutPosition) return;   
        
        $this->checkInfo(
            self::ATTACK, "G", "Aim", 3, $origin
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
        $fakeMapViolation->setMaxViolation(14);
        $fakeMapViolation->setMaxTicks(0.5);

        $deltaYaw = $profile->getDeltaYaw();

        $expectedHeadYaw = fmod(($packet->yaw > 0 ? 0 : 360) + $packet->yaw, 360);
        $diff = fmod(abs($expectedHeadYaw - $deltaYaw), 360);
        $roundedDiff = round($diff, 4);
        if($diff > 5E-5 && $roundedDiff !== 360.0 && $deltaYaw > 0){
            if($fakeMapViolation->handleViolation()){
                $this->handleViolation("D: ".$diff." R: ".$roundedDiff." Y: ".$deltaYaw." E: ".$expectedHeadYaw);
            }
        }elseif($deltaYaw < 0){
            $expectedHeadYaw = fmod($deltaYaw, 180);
            $diff = fmod(abs($expectedHeadYaw - $deltaYaw), 360);
            $roundedDiff = round($diff, 4);
            if($diff > 5E-5 && $roundedDiff !== 360.0){
                if($fakeMapViolation->handleViolation()){
                    $class = new class extends PacketCheck{
                        public function handle(VPacket $packet, string $origin) : void{
                            $this->checkInfo(
                                self::ATTACK, "2G", "Aim", 3, $origin
                            );
                        }
                    };
                    $class->handle($packet, $origin);
                    $class->handleViolation("D: ".$diff." R: ".$roundedDiff." Y: ".$deltaYaw." E: ".$expectedHeadYaw);
                }
            }
        }else{
            $fakeMapViolation->addViolation(-0.5);
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