<?php

namespace hachkingtohach1\vennv\check\checks\speed;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class SpeedG extends PacketCheck{

    private static array $deltaXOffGround = [];
    private static array $deltaZOffGround = [];
    private static array $fakeMapViolation = [];

    public function getCloning() : int{
        return 2;
    }

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayOutPosition) return;
        
        $this->checkInfo(
            self::MOVE, "G", "Speed", 2, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(8);
        $fakeMapViolation->setMaxTicks(0.5);

        $deathTicks = $profile->getDeathTicks();
        $respawnTicks = $profile->getRespawnTicks();
        $moveTicks = $profile->getMoveTicks();

        if($deathTicks < 2 && $respawnTicks < 2) return;

        $onGround = $profile->getOnGround();

        $location = $profile->getLocation();
        $vectorLocation = $location->toVector();

        $lastLocation = $profile->getLastLocation();
        $lastVectorLocation = $lastLocation->toVector();

        $deltaX = $profile->getDeltaX();
        $deltaZ = $profile->getDeltaZ();

        $speed = abs($deltaX + $deltaZ);

        if($speed <= 0.375) return;

        $groundX = $vectorLocation->getFloorX();
        $groundZ = $vectorLocation->getFloorZ();

        $lastGroundX = $lastVectorLocation->getFloorX();
        $lastGroundZ = $lastVectorLocation->getFloorZ();

        if($onGround && !$lastLocation->isOnGround()){
            if(
                !isset(self::$deltaXOffGround[$profile->getName()]) ||
                !isset(self::$deltaZOffGround[$profile->getName()])
            ) return;
    
            $deltaXOffGround = self::$deltaXOffGround[$profile->getName()];
            $deltaZOffGround = self::$deltaZOffGround[$profile->getName()];
    
            $direction = atan2(($lastGroundX - ($lastGroundX + $deltaXOffGround)), ($lastGroundZ - ($lastGroundZ + $deltaZOffGround))) * 180 / M_PI;
            $groundDirection = atan2(($lastGroundX - $groundX), ($lastGroundZ - $groundZ)) * 180 / M_PI;
            $difference = abs($groundDirection - $direction);
    
            if($difference > 30 && $difference < 360 - 30){
                if($fakeMapViolation->handleViolation()){
                    $this->handleViolation("D: ".$difference);
                }
                if($difference > 300 + $moveTicks * 100){
                    $class = new class extends PacketCheck{
                        public function handle(VPacket $packet, string $origin) : void{
                            $this->checkInfo(
                                self::MOVE, "2G", "Speed", 2, $origin
                            );
                        }
                    };
                    $class->handle($packet, $origin);
                    $class->handleViolation("D: ".$difference);
                }
            }
        }else{
            self::$deltaXOffGround[$profile->getName()] = $deltaX;
            self::$deltaZOffGround[$profile->getName()] = $deltaZ;
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