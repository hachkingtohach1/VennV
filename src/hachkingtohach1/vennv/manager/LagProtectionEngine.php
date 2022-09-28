<?php

namespace hachkingtohach1\vennv\manager;

use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\data\manager\DataManager;
use hachkingtohach1\vennv\storage\StorageEngine;
use hachkingtohach1\vennv\task\ServerTickTask;
use hachkingtohach1\vennv\task\ServerTickTask2;
use hachkingtohach1\vennv\VennVPlugin;

final class LagProtectionEngine{

    private int $lagThreshold = 0;
    private bool $recoverMode = false;
    private int $recoverTime = 0;

    public static function getInstance() : LagProtectionEngine{
        return new self;
    }

    public function getLagThreshold() : int{
        return $this->lagThreshold;
    }

    public function getRecoveredMode() : bool{
        return $this->recoverMode;
    }

    public function getRecoverTime() : int{
        return microtime(true) - $this->recoverTime;
    }

    public function setLagThreshold(int $lagThreshold) : void{
        $this->lagThreshold = $lagThreshold;
    }

    public function addLagThreshold(int $lagThreshold) : void{
        $this->lagThreshold += $lagThreshold;
    }

    public function setRecoveredMode(bool $recoverMode) : void{
        $this->recoverMode = $recoverMode;
    }

    public function setRecoverTime(int $recoverTime) : void{
        $this->recoverTime = $recoverTime;
    }

    public function isLagging() : bool{
        if($this->getRecoveredMode()){
            if($this->getRecoverTime() >= StorageEngine::getInstance()->getConfig()->getData(StorageEngine::TPS_PROTECTION_RECOVER_MILLIS)/1000){
                $this->setRecoveredMode(false);
                $this->setRecoverTime(0);
            }
            return true;
        }
        if(
            VennVPlugin::getPlugin()->getServer()->getTicksPerSecond() <= StorageEngine::getInstance()->getConfig()->getData(StorageEngine::TPS_PROTECTION_MIN_TPS)
        ){
            if($this->lagThreshold >= StorageEngine::getInstance()->getConfig()->getData(StorageEngine::TPS_PROTECTION_LAG_THRESHOLD)){
                PacketHandler::getInstance()->clear();
                $this->setLagThreshold(0);
                $this->setRecoveredMode(true);
            }
            $this->addLagThreshold(1);
        }
        if(DataManager::getAPIServer() === 3){
            if(ServerTickTask2::getInstance()->isLagging(microtime(true))){
                return true;
            }
        }else{
            if(ServerTickTask::getInstance()->isLagging(microtime(true))){
                return true;
            }
        }
        return false;
    }
}