<?php

namespace hachkingtohach1\vennv\command\subcommands;

use hachkingtohach1\vennv\storage\StorageEngine;
use hachkingtohach1\vennv\utils\TextFormat;
use pocketmine\player\Player as PlayerPm4;
use pocketmine\Player as PlayerPm3;

final class Reload{

    public function command(string $command, PlayerPm3|PlayerPm4 $player) : bool{
        if($command == StorageEngine::getInstance()->getConfig()->getData(StorageEngine::COMMANDS_SUBCOMMANDS_RELOAD_COMMAND)){
            StorageEngine::getInstance()->getConfig()->reload();
            $player->sendMessage(TextFormat::GREEN."Config reloaded!");         
        }
        return false;
    }
}