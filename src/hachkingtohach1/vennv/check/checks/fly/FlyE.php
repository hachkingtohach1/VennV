<?php

namespace hachkingtohach1\vennv\check\checks\fly;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class FlyE extends PacketCheck{

    private static array $fakeMapViolation = [];

    public function getCloning() : int{
        return 2;
    }

    public function handle(VPacket $packet, string $origin) : void{
        if(!$packet instanceof VPacketPlayInFlying) return;

        if($packet->isAllowed) return;
        
        $this->checkInfo(
            self::FLY, "E", "Fly", 3, $origin
        );

        $profile = $this->getProfile();

        if($profile->getPlacingBlock()){
            $profile->setPlacingBlock(false);
            return;
        }

        $gameMode = $profile->getGameMode();
        $skipGameMode = [VPacketPlayInChangeGameMode::CREATIVE, VPacketPlayInChangeGameMode::SPECTATOR];
        if(in_array($gameMode, $skipGameMode)){
            return;
        }

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(8);
        $fakeMapViolation->setMaxTicks(0.5);

        $joinTicks = $profile->getJoinTicks();
        $deathTicks = $profile->getDeathTicks();
        $respawnTicks = $profile->getRespawnTicks();

        $onGround = $profile->getOnGround();
        $onLiquid = $profile->isOnLiquid();

        $inVehicle = $profile->getInVehicle();

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();
        $lastLastLocation = $profile->getLastLastLocation();

        if((($deathTicks > $respawnTicks) || ($deathTicks > 3 && $respawnTicks > 3)) && !$inVehicle && !$onLiquid && !$onGround && !$location->isOnGround() && !$lastLocation->isOnGround() && !$lastLastLocation->isOnGround() && $joinTicks > 2){
            if($location->getY() === $lastLocation->getY()){
                if($fakeMapViolation->handleViolation()){
                    $this->handleViolation("Y: ".$location->getY());
                }
            }else{
                $fakeMapViolation->addViolation(-0.05);
            }

            $class = new class extends PacketCheck{
                public function handle(VPacket $packet, string $origin) : void{
                    $this->checkInfo(
                        self::FLY, "2E", "Fly", 15, $origin
                    );
                }
            };
            $class->handle($packet, $origin);
            if($location->getY() === $lastLastLocation->getY()){
                if($fakeMapViolation->handleViolation()){
                    $class->handleViolation("Y: ".$location->getY());
                }
            }else{
                $class->addViolation(-1);
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
        return !empty(self::$fakeMapViolation[$profileName]);
    }
}