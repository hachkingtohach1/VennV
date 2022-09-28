<?php

namespace hachkingtohach1\vennv\alert;

use hachkingtohach1\vennv\alert\manager\AlertManager;
use hachkingtohach1\vennv\data\manager\DataManager;
use hachkingtohach1\vennv\machine\MachineLearning;
use hachkingtohach1\vennv\manager\ResetViolationEngine;
use hachkingtohach1\vennv\manager\SetBackEngine;
use hachkingtohach1\vennv\storage\StorageEngine;
use hachkingtohach1\vennv\type\VennVTypeLoader;
use hachkingtohach1\vennv\utils\DispatchCommandUtil;
use hachkingtohach1\vennv\utils\ReplaceText;
use hachkingtohach1\vennv\VennVPlugin;

class Alert extends AlertManager{

    private static array $handler = [];

    public static function getInstance() : self{
        return new ALert();
    }

    public function getHandle(string $cheater, string $cheat) : mixed{
        if(empty(self::$handler[$cheater])){
            self::$handler[$cheater] = [];
        }
        if(empty(self::$handler[$cheater][$cheat])){
            self::$handler[$cheater][$cheat] = 0;
        }
        return self::$handler[$cheater][$cheat];
    }

    public function removeHandle(string $cheater) : void{
        if(isset(self::$handler[$cheater])) unset(self::$handler[$cheater]);
    }

    public function addHandle(string $cheater, string $cheat, int|float $vl) : void{
        if(empty(self::$handler[$cheater])){
            self::$handler[$cheater] = [];
        }
        if(empty(self::$handler[$cheater][$cheat])){
            self::$handler[$cheater][$cheat] = 0;
        }
        $handle = self::$handler[$cheater][$cheat];
        $nextHandle = $handle + $vl;
        if($handle >= 0 && $nextHandle >= 0){
            self::$handler[$cheater][$cheat] += $vl;
        }else{
            self::$handler[$cheater][$cheat] = 0;
        }       
    }

    public function Alert(string $cheater, string $cheat, int $type, string $subType, int|float $vl, int|float $maxVl, string $parameter = "") : string{
        if(ResetViolationEngine::canReset()){
            self::$handler = [];
        }
        if(empty(self::$handler[$cheater])){
            self::$handler[$cheater] = [];
        }
        if(empty(self::$handler[$cheater][$cheat.$subType])){
            self::$handler[$cheater][$cheat.$subType] = 0;
        }
        $this->addHandle($cheater, $cheat.$subType, $vl);

        $cheatData = StorageEngine::getInstance()->getConfig()->getData('checks.'.strtolower($cheat).'.'.$subType);
        if($cheatData !== null){
            $cancel = $cheatData['cancel'];
            $cancel ? SetBackEngine::addHandler($cheater, $type) : null;
        }
        
        if($this->getHandle($cheater, $cheat.$subType) >= $maxVl){
            
            //MachineLearning Training
            //$machineLearning = new MachineLearning();
            //$machineLearning->train(DataManager::getPlayerData($cheater));

            $database = StorageEngine::getInstance()->getAllDataBase();
            foreach($database as $class){
                $class->checkDataBase();
                $class->addHasPunished($cheater);
            }
            if(!VennVTypeLoader::getInstance()->isProxy()){
                foreach(VennVPlugin::getPlugin()->getServer()->getOnlinePlayers() as $player){
                    if(strtolower($player->getName()) === strtolower($cheater)){
                        $cheatData = StorageEngine::getInstance()->getConfig()->getData('checks.'.strtolower($cheat).'.'.$subType);
                        if($cheatData !== null){

                            $kick = $cheatData['kick'];
                            $ban = $cheatData['ban'];
                            $cancel = $cheatData['cancel'];

                            $mode = $kick === true && $ban === true ? rand(1, 2) : false;
                            $mode === false ? ($kick === true ? $mode = 1 : false) : false;
                            $mode === false ? ($ban === true ? $mode = 2 : false) : false;
                            
                            if($mode === 1){
                                $reason = StorageEngine::getInstance()->getConfig()->getData(StorageEngine::KICK_MESSAGE);
                                foreach(StorageEngine::getInstance()->getConfig()->getData(StorageEngine::KICK_COMMANDS) as $command){
                                    DispatchCommandUtil::dispatchCommand(
                                        ReplaceText::replace($command, $player->getName(), $cheat, $this->getHandle($cheater, $cheat.$subType), "", $reason)
                                    );
                                }                           
                            }elseif($mode === 2){
                                $reason = StorageEngine::getInstance()->getConfig()->getData(StorageEngine::BAN_MESSAGE);
                                foreach(StorageEngine::getInstance()->getConfig()->getData(StorageEngine::BAN_COMMANDS) as $command){
                                    DispatchCommandUtil::dispatchCommand(
                                        ReplaceText::replace($command, $player->getName(), $cheat, $this->getHandle($cheater, $cheat.$subType), "", $reason)
                                    );
                                }
                            }

                            $parameter .= " [Penalty received]";
                        }
                    }
                }
            }
        }
        return $this->handleAlert($cheater, $cheat.' '.$subType, $this->getHandle($cheater, $cheat.$subType), $parameter);
    }
}