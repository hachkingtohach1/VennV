<?php

namespace hachkingtohach1\vennv\listener;

use hachkingtohach1\vennv\compat\PacketManager;
use hachkingtohach1\vennv\compat\packets\VLoginGamePacket;
use hachkingtohach1\vennv\compat\packets\VLogoutGamePacket;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInAttackEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockDig;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockPlace;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInCloseWindow;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInDeath;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInHeldItemSlot;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInReceivingPing;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInSleeping;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInSneaking;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityEffect;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityTeleport;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutOpenWindow;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutRespawn;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutSleeping;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutUnderAttack;
use hachkingtohach1\vennv\data\manager\DataManager;
use hachkingtohach1\vennv\utils\EffectUtil;
use hachkingtohach1\vennv\utils\ItemUtil;
use hachkingtohach1\vennv\utils\PacketUtil;
use hachkingtohach1\vennv\utils\PingUtil;
use hachkingtohach1\vennv\utils\PlayerMoveUtil;
use hachkingtohach1\vennv\utils\Vector;
use hachkingtohach1\vennv\VennVPlugin;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityEffectRemoveEvent;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\player\PlayerBedEnterEvent;
use pocketmine\event\player\PlayerBedLeaveEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\player\Player as PlayerPm4;
use pocketmine\Player as PlayerPm3;

class DataListener implements Listener{
	use PacketUtil;

	public function onDataPacketReceive(DataPacketReceiveEvent $event) : void{
		foreach(VennVPlugin::getPlugin()->getServer()->getOnlinePlayers() as $player){
			$data = DataManager::getPlayerData($player->getName());	
			$baseLocation = $player->getLocation();
			$location = $data->getLocation();
			$bx = $baseLocation->getX();
			$by = $baseLocation->getY();
			$bz = $baseLocation->getZ();
			$x = $location->getX();
			$y = $location->getY();
			$z = $location->getZ();
			$vector = new Vector();
			$vector->set($bx, $by, $bz);
			$vector2 = new Vector($x, $y, $z);
			$vector2->set($x, $y, $z);
			$delta = $vector2->distanceSquared($vector);
			$deltaAngle = abs($location->getYaw() - $baseLocation->getYaw()) + abs($location->getPitch() - $baseLocation->getPitch());
			if($delta > 0.0001 || $deltaAngle > 1.0){
				PlayerMoveUtil::move($player);
				PlayerMoveUtil::checkBlocks($player, $deltaAngle > 1.0);
			}
			EffectUtil::checkEffects($player);
		}
		PacketManager::getInstance()->listenPackets();
		$convert = $this->convertPacketByAPI($event);
		foreach($convert as $data){
			$this->convertPacket($data[0], $data[1]);	
		}
	}

	public function onDataPacketSend(DataPacketSendEvent $event) : void{
		$convert = $this->convertPacketByAPI($event);
		foreach($convert as $data){
			$this->convertPacket($data[0], $data[1]);	
		}
	}

	public function onPlayerJoin(PlayerJoinEvent $event) : void{
		$player = $event->getPlayer();
		$loginPacket = new VLoginGamePacket();
		$loginPacket->origin = $player->getName();
		$loginPacket->handle();
	}

	public function onPlayerQuit(PlayerQuitEvent $event) : void{
		$player = $event->getPlayer();
		$logoutPacket = new VLogoutGamePacket();
		$logoutPacket->origin = $player->getName();
		$logoutPacket->handle();
	}

	public function onPlayerMoveEvent(PlayerMoveEvent $event) : void{
		$player = $event->getPlayer();
		$pingPacket = new VPacketPlayInReceivingPing();
		$pingPacket->origin = $player->getName();
		$pingPacket->ping = PingUtil::getPing($player);
		$pingPacket->handle();
	}

	public function onEntityTeleport(EntityTeleportEvent $event) : void{
		$entity = $event->getEntity();
		if($entity instanceof PlayerPm3 || $entity instanceof PlayerPm4){
			$packetTeleport = new VPacketPlayOutEntityTeleport();
			$packetTeleport->x = $entity->getLocation()->getX();
			$packetTeleport->y = $entity->getLocation()->getY();
			$packetTeleport->z = $entity->getLocation()->getZ();
			$packetTeleport->yaw = $entity->getLocation()->getYaw();
			$packetTeleport->pitch = $entity->getLocation()->getPitch();
			$packetTeleport->onGround = $entity->isOnGround();
			$packetTeleport->origin = $entity->getName();
			$packetTeleport->handle();
		}
	}

	public function onPlayerBedEnter(PlayerBedEnterEvent $event) : void{
		$player = $event->getPlayer();
		$packetSleeping = new VPacketPlayInSleeping();
		$packetSleeping->x = $player->getLocation()->getX();
		$packetSleeping->y = $player->getLocation()->getY();
		$packetSleeping->z = $player->getLocation()->getZ();
		$packetSleeping->yaw = $player->getLocation()->getYaw();
		$packetSleeping->pitch = $player->getLocation()->getPitch();		
		$packetSleeping->origin = $player->getName();
		$packetSleeping->handle();
	}

	public function onPlayerBedLeave(PlayerBedLeaveEvent $event) : void{
		$player = $event->getPlayer();
		$packetSleeping = new VPacketPlayOutSleeping();
		$packetSleeping->x = $player->getLocation()->getX();
		$packetSleeping->y = $player->getLocation()->getY();
		$packetSleeping->z = $player->getLocation()->getZ();
		$packetSleeping->yaw = $player->getLocation()->getYaw();
		$packetSleeping->pitch = $player->getLocation()->getPitch();		
		$packetSleeping->origin = $player->getName();
		$packetSleeping->handle();
	}

	public function onPlayerToggleSneak(PlayerToggleSneakEvent $event) : void{
		$player = $event->getPlayer();
		$packetSneaking = new VPacketPlayInSneaking();
		$packetSneaking->origin = $player->getName();
		$packetSneaking->handle();
	}

	public function onPlayerDeath(PlayerDeathEvent $event) : void{
		$player = $event->getPlayer();
		$packetDeath = new VPacketPlayInDeath();
		$packetDeath->origin = $player->getName();
		$packetDeath->handle();
	}

	public function onPlayerRespawn(PlayerRespawnEvent $event) : void{
		$player = $event->getPlayer();
		$packetRespawn = new VPacketPlayOutRespawn();
		$packetRespawn->origin = $player->getName();
		$packetRespawn->handle();
	}

	public function onEntityDamageByEntity(EntityDamageByEntityEvent $event) : void{
		$damager = $event->getDamager();
		$entity = $event->getEntity();
		if($entity instanceof PlayerPm3 || $entity instanceof PlayerPm4){
			$underAttackPacket = new VPacketPlayOutUnderAttack();
			$underAttackPacket->originX = $entity->getLocation()->getX();
			$underAttackPacket->originY = $entity->getLocation()->getY();
			$underAttackPacket->originZ = $entity->getLocation()->getZ();
			$underAttackPacket->attackerX = $damager->getLocation()->getX();
			$underAttackPacket->attackerY = $damager->getLocation()->getY();
			$underAttackPacket->attackerZ = $damager->getLocation()->getZ();
			$underAttackPacket->origin = $entity->getName();
			$underAttackPacket->handle();
		}
		if($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK){
			if($damager instanceof PlayerPm3 || $damager instanceof PlayerPm4){
				$attackPacket = new VPacketPlayInAttackEntity();
				$attackPacket->originX = $damager->getLocation()->getX();
				$attackPacket->originY = $damager->getLocation()->getY();
				$attackPacket->originZ = $damager->getLocation()->getZ();
				$attackPacket->originYaw = $damager->getLocation()->getYaw();
				$attackPacket->originPitch = $damager->getLocation()->getPitch();
				$attackPacket->targetX = $entity->getLocation()->getX();
				$attackPacket->targetY = $entity->getLocation()->getY();
				$attackPacket->targetZ = $entity->getLocation()->getZ();
				$attackPacket->targetYaw = $entity->getLocation()->getYaw();
				$attackPacket->targetPitch = $entity->getLocation()->getPitch();
				$attackPacket->origin = $damager->getName();
				$attackPacket->handle();
			}
		}
	}

	public function onPlayerGameModeChange(PlayerGameModeChangeEvent $event) : void{
		$player = $event->getPlayer();
		$gamemode = $event->getNewGamemode();
		$gamemodePacket = new VPacketPlayInChangeGameMode();
		if(DataManager::getAPIServer() === 3){
			$gamemodePacket->gamemode = $gamemode;
		}else{
			$gamemodePacket->gamemode = (int)$gamemode->getAliases()[2];
		}
		$gamemodePacket->origin = $player->getName();
		$gamemodePacket->handle();
	}

	public function onBlockPlace(BlockPlaceEvent $event) : void{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$placeBlockPacket = new VPacketPlayInBlockPlace();
		if(DataManager::getAPIServer() === 3){
			if($block->getY() > $player->getLocation()->getY()){
				$placeBlockPacket->face = VPacketPlayInBlockPlace::UP;
			}else{
				$placeBlockPacket->face = VPacketPlayInBlockPlace::DOWN;
			}		
			$placeBlockPacket->x = $block->getX();
			$placeBlockPacket->y = $block->getY();
			$placeBlockPacket->z = $block->getZ();
		}else{
			if($block->getPosition()->getY() > $player->getLocation()->getY()){
				$placeBlockPacket->face = VPacketPlayInBlockPlace::UP;
			}else{
				$placeBlockPacket->face = VPacketPlayInBlockPlace::DOWN;
			}		
			$placeBlockPacket->x = $block->getPosition()->getX();
			$placeBlockPacket->y = $block->getPosition()->getY();
			$placeBlockPacket->z = $block->getPosition()->getZ();
		}
		$placeBlockPacket->origin = $player->getName();
		$placeBlockPacket->handle();
	}

	public function onBlockBreak(BlockBreakEvent $event) : void{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$digBlockPacket = new VPacketPlayInBlockDig();
		if(DataManager::getAPIServer() === 3){
			if($block->getY() > $player->getLocation()->getY()){
				$digBlockPacket->face = VPacketPlayInBlockDig::UP;
			}else{
				$digBlockPacket->face = VPacketPlayInBlockDig::DOWN;
			}		
			$digBlockPacket->x = $block->getX();
			$digBlockPacket->y = $block->getY();
			$digBlockPacket->z = $block->getZ();
		}else{
			if($block->getPosition()->getY() > $player->getLocation()->getY()){
				$digBlockPacket->face = VPacketPlayInBlockDig::UP;
			}else{
				$digBlockPacket->face = VPacketPlayInBlockDig::DOWN;
			}		
			$digBlockPacket->x = $block->getPosition()->getX();
			$digBlockPacket->y = $block->getPosition()->getY();
			$digBlockPacket->z = $block->getPosition()->getZ();
		}
		$digBlockPacket->origin = $player->getName();
		$digBlockPacket->handle();
	}

	public function onInventoryOpen(InventoryOpenEvent $event) : void{
		$player = $event->getPlayer();
		$openWindowPacket = new VPacketPlayOutOpenWindow();
		$openWindowPacket->origin = $player->getName();
		$openWindowPacket->handle();
	}

	public function onInventoryClose(InventoryCloseEvent $event) : void{
		$player = $event->getPlayer();
		$closeWindowPacket = new VPacketPlayInCloseWindow();
		$closeWindowPacket->origin = $player->getName();
		$closeWindowPacket->handle();
	}

	public function onEntityEffectRemove(EntityEffectRemoveEvent $event) : void{
		$entity = $event->getEntity();
		$effect = $event->getEffect();
		if($entity instanceof PlayerPm3 || $entity instanceof PlayerPm4){
			$effectPacket = new VPacketPlayOutEntityEffect();
			if(DataManager::getAPIServer() === 3){
				$effectPacket->effectId = EffectUtil::toEffectId($effect->getType()->getName());
			}else{
				$effectPacket->effectId = EffectUtil::toEffectId($effect->getType()->getName()->getText());
			}
			$effectPacket->amplifier = $effect->getAmplifier();
            $effectPacket->duration = $effect->getDuration();
			$effectPacket->flags = VPacketPlayOutEntityEffect::REMOVE;
			$effectPacket->origin = $entity->getName();
			$effectPacket->handle();
		}
	}

	public function onPlayerItemHeld(PlayerItemHeldEvent $event) : void{
		$player = $event->getPlayer();
		$item = $event->getItem();
		$slot = $event->getSlot();
		$heldItemPacket = new VPacketPlayInHeldItemSlot();
		$heldItemPacket->id = ItemUtil::getItemId($item);
		$heldItemPacket->meta = ItemUtil::getItemMeta($item);
		$heldItemPacket->slot = $slot;
		$heldItemPacket->name = $item->getCustomName();
		$heldItemPacket->lore = $item->getLore();
		$heldItemPacket->origin = $player->getName();
		$heldItemPacket->handle();
	}
}