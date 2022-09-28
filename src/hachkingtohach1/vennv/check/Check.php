<?php

namespace hachkingtohach1\vennv\check;

use hachkingtohach1\vennv\alert\Alert;
use hachkingtohach1\vennv\api\events\ViolationEvent;
use hachkingtohach1\vennv\data\manager\DataManager;
use hachkingtohach1\vennv\data\manager\PlayerData;
use hachkingtohach1\vennv\manager\LagProtectionEngine;
use hachkingtohach1\vennv\storage\StorageEngine;
use hachkingtohach1\vennv\type\VennVTypeLoader;
use hachkingtohach1\vennv\VennVPlugin;

class Check{

    private int $type;
    private string $subType;
    private string $friendlyName;
    private int|float $maxViolation;
    private string $profileName;
    private static array $delayedAlerts = [];

    public const ATTACK = 1, MOVE = 2, FLY = 3, INVENTORY = 4, BADPACKETS = 5, MISC = 6, INTERACT = 7;

    public function checkInfo(int $type, string $subType, string $friendlyName, int|float $maxViolation, string $profileName) : void{
        $this->type = $type;
        $this->subType = $subType;
        $this->friendlyName = $friendlyName;
        $this->maxViolation = $maxViolation;
        $this->profileName = $profileName;
    }

    public function getProfile() : PlayerData{
        return DataManager::getPlayerData($this->profileName);
    }

    public function getCloning() : int{
        return 1;
    }

    public function getType() : int{
        return $this->type;
    }

    public function getSubType() : string{
        return $this->subType;
    }

    public function getFriendlyName() : string{
        return $this->friendlyName;
    }

    public function getMaxViolation() : int|float{
        return $this->maxViolation;
    }

    public function getProfileName() : string{
        return $this->profileName;
    }

    public function getViolations() : int|float{
        return Alert::getInstance()->getHandle($this->getProfileName(), $this->getFriendlyName().$this->getSubType());
    }

    public function addViolation(int|float $vl) : void{
        Alert::getInstance()->addHandle($this->getProfileName(), $this->getFriendlyName().$this->getSubType(), $vl);
    }

    private function handleDelayAlert() : bool{
        if(!isset(self::$delayedAlerts[$this->profileName])){
            self::$delayedAlerts[$this->profileName] = microtime(true);
            return true;
        }
        if(microtime(true) - self::$delayedAlerts[$this->profileName] > 0.2){
            self::$delayedAlerts[$this->profileName] = microtime(true);
            return true;
        }
        return false;
    }

    public function handleViolation(string $message = "", int|float $vl = 1.0) : void{
        $event = new ViolationEvent();
        $event->set($this->profileName, $this->friendlyName, $message, $vl);
        $event->call();
        if(!$event->isCancelled() && !LagProtectionEngine::getInstance()->isLagging()){           
            if(!VennVTypeLoader::getInstance()->isProxy()){
                foreach(VennVPlugin::getPlugin()->getServer()->getOnlinePlayers() as $player){
                    if(strtolower($player->getName()) === strtolower($this->profileName)){
                        $alert = Alert::getInstance()->Alert($this->profileName, $this->friendlyName, $this->type, $this->subType, $vl, $this->maxViolation, $message);
                        if(StorageEngine::getInstance()->getConfig()->getData(StorageEngine::ALERTS_ENABLE) === true){
                            if($this->handleDelayAlert()){
                                $player->sendMessage($alert);
                            }
                        }
                    }
                }
            }
        }
    }
}