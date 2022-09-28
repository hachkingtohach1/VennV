<?php

namespace hachkingtohach1\vennv\data\manager;

use hachkingtohach1\vennv\storage\StorageEngine;

class DataManager{

    private static array $playerData = [];

    public static function getAPIServer() : int{
        return StorageEngine::getInstance()->getConfig()->getData(StorageEngine::POCKETMINE_API);
    }

    public static function getPlayerData(string $name) : PlayerData{
        if(!isset(self::$playerData[$name])){
            $data = new PlayerData();
            $data->setName($name);
            self::$playerData[$name] = $data;           
        }
        return self::$playerData[$name];
    }

    public static function inject(string $name, mixed $data) : void{
        if(!isset(self::$playerData[$name])){
            self::$playerData[$name] = $data;
        }       
    }

    public static function uninject(string $name) : void{
        if(isset(self::$playerData[$name])){
            unset(self::$playerData[$name]);
        } 
    }
}