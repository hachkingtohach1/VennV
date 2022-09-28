<?php

namespace hachkingtohach1\vennv\check\checks\fly;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;
use hachkingtohach1\vennv\utils\MathUtil;
use hachkingtohach1\vennv\utils\Vector;

class FlyI extends PacketCheck{

    private static array $fakeMapViolation = [];
    private static array $ticks = [];
    private static array $lastMovement = [];

    public function getCloning() : int{
        return 3;
    }

    public function handle(VPacket $packet, string $origin) : void{     
        if(!$packet instanceof VPacketPlayInFlying) return;

        if($packet->isAllowed) return;
        
        $this->checkInfo(
            self::FLY, "I", "Fly", 5, $origin
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
        $fakeMapViolation->setMaxViolation(12);
        $fakeMapViolation->setMaxTicks(0.5);

        $location = $profile->getLocation();
        $lastLocation = $profile->getLastLocation();

        $onLiquid = $profile->isOnLiquid();

        if($onLiquid) return;

        if(!isset(self::$lastMovement[$profile->getName()])){
            self::$lastMovement[$profile->getName()] = new Vector();
        }

        if($lastLocation->isOnGround()){
            self::$ticks[$profile->getName()] = 0;
        }else{
            if(isset(self::$ticks[$profile->getName()]) && isset(self::$lastMovement[$profile->getName()])){
                $lastMovement = self::$lastMovement[$profile->getName()];
                if(self::$ticks[$profile->getName()]++ > 9){
                    $pitch = $lastLocation->getPitch();
                    $yaw = $lastLocation->getYaw();
                    $mPitch = $pitch * 0.017453292;
                    $deltaY = $lastLocation->getY() - $location->getY();
                    $lastMoveY = $lastMovement->getY();
                    $lastMoveLen = $lastMovement->length();
                    $rotateVector = self::getVectorForRotation($pitch, $yaw);
                    $vectorLen = $rotateVector->length();
                    $d = sqrt(pow($rotateVector->getX(), 2.0) + pow($rotateVector->getZ(), 2.0));
                    $f = pow(cos($mPitch), 2.0) * min(1.0, $vectorLen / 0.4);
                    $d1 = $deltaY / 0.9900000095367432;
                    $d2 = ($lastMoveY - 0.08) * 0.9800000190734863;
                    if($mPitch < 0){
                        $d1 -= $lastMoveLen * (-sin($mPitch)) * 0.04 * 3.2;
                    }
                    if($lastMoveY < 0.0 && $d > 0.0){
                        $d1 -= $lastMoveY * -0.1 * $f;
                    }
                    $d1 -= 0.08 * (-1.0 + $f * 0.75);
                    $lastMoveLow = MathUtil::lowestAbs($lastMoveY - $d1, $deltaY - $d2);
                    $lowest = MathUtil::lowestAbs(($d1 - $lastMoveY) / $d1, ($deltaY - $d2) / $d2);
                    if(abs($lastMoveLow) > 0.025 && abs($lowest) > 0.075){
                        if($fakeMapViolation->handleViolation()){
                            $this->handleViolation("LML: ".$lastMoveLow." L: ".$lowest);
                        }
                    }
                    $class = new class extends PacketCheck{
                        public function handle(VPacket $packet, string $origin) : void{
                            $this->checkInfo(
                                self::FLY, "2I", "Fly", 7, $origin
                            );
                        }
                    };
                    $class->handle($packet, $origin);
                    if(abs($lastMoveLow) < 0.05 && abs($lowest) == 1){
                        $class->handleViolation("LML: ".$lastMoveLow." L: ".$lowest);
                    }else{
                        $class->addViolation(-0.05);
                    }
                    $class2 = new class extends PacketCheck{
                        public function handle(VPacket $packet, string $origin) : void{
                            $this->checkInfo(
                                self::FLY, "2I2", "Fly", 5, $origin
                            );
                        }
                    };
                    $class2->handle($packet, $origin);
                    if(abs($lastMoveLow) < 0.05 && abs($lowest) == 1){
                        $class2->handleViolation("LML: ".$lastMoveLow." L: ".$lowest);
                    }else{
                        $class2->addViolation(-1);
                    }
                }
                $lastMovement->setX($lastLocation->getX() - $location->getX());
                $lastMovement->setY($lastLocation->getY() - $location->getY());
                $lastMovement->setZ($lastLocation->getZ() - $location->getZ());
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

    private static function getVectorForRotation(int|float $f, int|float $f1) :Vector{
        $location2 = cos(-$f1 * 0.017453292 - 3.1415927);
        $var3 = sin(-$f1 * 0.017453292 - 3.1415927);
        $var4 = -cos(-$f * 0.017453292);
        $pitch = sin(-$f * 0.017453292);
        $vector = new Vector();
        $vector->set($var3 * $var4, $pitch, $location2 * $var4);
        return $vector;
    }
}