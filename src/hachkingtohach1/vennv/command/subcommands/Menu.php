<?php

namespace hachkingtohach1\vennv\command\subcommands;

use hachkingtohach1\vennv\form\manager\FormManager;
use hachkingtohach1\vennv\storage\StorageEngine;
use pocketmine\player\Player as PlayerPm4;
use pocketmine\Player as PlayerPm3;

final class Menu{

    public function command(string $command, PlayerPm3|PlayerPm4 $player) : bool{
        if($command == StorageEngine::getInstance()->getConfig()->getData(StorageEngine::COMMANDS_SUBCOMMANDS_MENU_COMMAND)){
            FormManager::mainForm($player);
        }
        return false;
    }
}