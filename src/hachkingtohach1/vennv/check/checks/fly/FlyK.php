<?php

namespace hachkingtohach1\vennv\check\checks\fly;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class FlyK extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function getCloning() : int{
        return 3;
    }

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayInFlying) return;

        if($packet->isAllowed) return;
        
        $this->checkInfo(
            self::FLY, "K", "Fly", 3, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(5);
        $fakeMapViolation->setMaxTicks(0.5);

        $onGround = $profile->getOnGround();

        $speed = $profile->getSpeed();

        $onLiquid = $profile->isOnLiquid();
        $onIce = $profile->isOnIce();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();
        $lastLastLocation = $profile->getLastLastLocation();

        $deltaX = $profile->getDeltaX();
        $lastDeltaX = $profile->getLastDeltaX();

        $teleportTicks = $profile->getTeleportTicks();

        if($deltaX != 0 && $lastDeltaX != 0 && !$onGround && !$onLiquid && !$location->isOnGround() && !$lastLocation->isOnGround() && !$lastLastLocation->isOnGround()){
            if($teleportTicks > 2){
                $limit = $speed * 120;
                $limit += $onIce ? $limit * 2 : 0;
                $limit2 = $speed * 150;
                $limit2 += $onIce ? $limit * 2 : 0;
                $distX = $location->getX() - $lastLocation->getX();
                $distZ = $location->getZ() - $lastLocation->getZ();
                $dist = ($distX * $distX) + ($distZ * $distZ);
                $lastDist = $dist;
                $shiftedLastDist = $lastDist * 0.91;
                $equalness = $dist - $shiftedLastDist;
                $scaledEqualness = $equalness * 138;  
                if($scaledEqualness > $limit){
                    $fvl = $fakeMapViolation->getViolations() + 1;
                    if($fakeMapViolation->handleViolation()){
                        $this->handleViolation("S: ".$scaledEqualness." F: ".$fvl);
                    }
                }else{
                    $fakeMapViolation->addViolation(-0.05);
                }
                $class = new class extends PacketCheck{
                    public function handle(VPacket $packet, string $origin) : void{
                        $this->checkInfo(
                            self::FLY, "2K", "Fly", 1, $origin
                        );
                    }
                };
                $class->handle($packet, $origin);
                if($scaledEqualness > $limit2){
                    $class->handleViolation("S: ".$scaledEqualness);
                }
                $class2 = new class extends PacketCheck{
                    public function handle(VPacket $packet, string $origin) : void{
                        $this->checkInfo(
                            self::FLY, "2K2", "Fly", 20, $origin
                        );
                    }
                };
                $class2->handle($packet, $origin);
                if($scaledEqualness > $limit){
                    $class2->handleViolation("S: ".$scaledEqualness);
                }else{
                    $class2->addViolation(-1);
                }
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