<?php

namespace hachkingtohach1\vennv\utils;

use hachkingtohach1\vennv\data\manager\DataManager;
use pocketmine\Server;
use pocketmine\command\ConsoleCommandSender as CCSPm3;
use pocketmine\console\ConsoleCommandSender as CCSPm4;

class DispatchCommandUtil{

    public static function dispatchCommand(string $command) : void{
        if(DataManager::getAPIServer() === 3){
            Server::getInstance()->dispatchCommand(new CCSPm3(Server::getInstance(), Server::getInstance()->getLanguage()), $command);
        }else{
            Server::getInstance()->dispatchCommand(new CCSPm4(Server::getInstance(), Server::getInstance()->getLanguage()), $command);
        }
    }
}