<?php

namespace hachkingtohach1\vennv\command;

use hachkingtohach1\vennv\command\subcommands\Reload;
use hachkingtohach1\vennv\command\subcommands\Menu;
use hachkingtohach1\vennv\storage\StorageEngine;
use hachkingtohach1\vennv\utils\EventUtil;
use hachkingtohach1\vennv\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;

class CommandListener implements Listener{
	
	public function onCommandEvent(CommandEvent $event) : void{
		$command = $event->getCommand();
		$sender = $event->getSender();
		$command = explode(" ", $command);
		$cmd = array_shift($command);
		$args = $command;
		$commands = StorageEngine::getInstance()->getConfig()->getData(StorageEngine::COMMANDS_NAME_COMMAND);
		if(in_array($cmd, $commands)){
			if(!isset($args[0])){
				$subCommands = [
					StorageEngine::getInstance()->getConfig()->getData(StorageEngine::COMMANDS_SUBCOMMANDS_MENU_COMMAND),
					StorageEngine::getInstance()->getConfig()->getData(StorageEngine::COMMANDS_SUBCOMMANDS_RELOAD_COMMAND)
				];
				$sender->sendMessage(TextFormat::RED."Usage: ".$cmd." <".implode("|", $subCommands).">");
				return;
			}
			switch($args[0]){
				case StorageEngine::getInstance()->getConfig()->getData(StorageEngine::COMMANDS_SUBCOMMANDS_RELOAD_COMMAND):
					(new Reload())->command($args[0], $sender);
					break;
				case StorageEngine::getInstance()->getConfig()->getData(StorageEngine::COMMANDS_SUBCOMMANDS_MENU_COMMAND):
					(new Menu())->command($args[0], $sender);
					break;
				default:
					$sender->sendMessage("VennV Anticheat commands:"); 
					$sender->sendMessage(
						StorageEngine::getInstance()->getConfig()->getData(StorageEngine::COMMANDS_SUBCOMMANDS_MENU_COMMAND).
						StorageEngine::getInstance()->getConfig()->getData(StorageEngine::COMMANDS_SUBCOMMANDS_MENU_DESCRIPTION)
					);
					$sender->sendMessage(
						StorageEngine::getInstance()->getConfig()->getData(StorageEngine::COMMANDS_SUBCOMMANDS_RELOAD_COMMAND).
						StorageEngine::getInstance()->getConfig()->getData(StorageEngine::COMMANDS_SUBCOMMANDS_RELOAD_DESCRIPTION)
					);
					break;
			}
			EventUtil::cancel($event);
		}
	}
}