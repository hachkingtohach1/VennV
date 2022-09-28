<?php

namespace hachkingtohach1\vennv\listener;

use hachkingtohach1\vennv\check\Check;
use hachkingtohach1\vennv\manager\SetBackEngine;
use hachkingtohach1\vennv\utils\EventUtil;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\player\Player as PlayerPm4;
use pocketmine\Player as PlayerPm3;

class SetBackListener implements Listener{

	public function onPlayerMove(PlayerMoveEvent $event) : void{
		$player = $event->getPlayer();
		$setbacks = [Check::MOVE, Check::FLY];
		foreach($setbacks as $setback){
			if(SetBackEngine::canSetBack($player->getName(), $setback)){
				EventUtil::cancel($event);
			}
		}
	}

	public function onEntityDamageByEntity(EntityDamageByEntityEvent $event) : void{
		$damager = $event->getDamager();
		if($damager instanceof PlayerPm3 || $damager instanceof PlayerPm4){
			if(SetBackEngine::canSetBack($damager->getName(), Check::ATTACK)){
				EventUtil::cancel($event);
			}
		}
	}

	public function onBlockBreak(BlockBreakEvent $event) : void{
		$player = $event->getPlayer();
		if(SetBackEngine::canSetBack($player->getName(), Check::INTERACT)){
			EventUtil::cancel($event);
		}
	}

	public function onBlockPlace(BlockPlaceEvent $event) : void{
		$player = $event->getPlayer();
		if(SetBackEngine::canSetBack($player->getName(), Check::INTERACT)){
			EventUtil::cancel($event);
		}
	}
}