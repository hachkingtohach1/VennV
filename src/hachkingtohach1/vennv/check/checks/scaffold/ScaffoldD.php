<?php

namespace hachkingtohach1\vennv\check\checks\scaffold;

use hachkingtohach1\vennv\check\types\PacketCheck;
use hachkingtohach1\vennv\compat\block\BlockLegacyIds;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockPlace;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\utils\FakeMapViolation;

class ScaffoldD extends PacketCheck{

    private static array $fakeMapViolation = [];
    
    public function handle(VPacket $packet, string $origin) : void{

        $this->checkInfo(
            self::INTERACT, "D", "Scaffold", 3, $origin
        );

        $profile = $this->getProfile();

        if(!$this->isHaveFakeMapViolation($profile->getName())){
            $this->setFakeMapViolation($profile->getName());
        }

        $fakeMapViolation = $this->getFakeMapViolation($profile->getName());
        $fakeMapViolation->setMaxViolation(4);
        $fakeMapViolation->setTicks(0.5);

        $blocks = [];
        foreach((new BlockLegacyIds())->getAllBlocks() as $name => $id){
            $blocks[$id] = $name;
        }


        $itemHeld = $profile->getIdItemHeld();

        if($packet instanceof VPacketPlayInBlockPlace){
            if(!in_array($itemHeld, $blocks)){
                if($fakeMapViolation->handleViolation()){
                    $this->handleViolation("IH: ".$itemHeld);
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
        return !empty(self::$fakeMapViolation[$profileName]);
    }
}