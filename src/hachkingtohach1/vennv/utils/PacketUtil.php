<?php

namespace hachkingtohach1\vennv\utils;

use hachkingtohach1\vennv\compat\packets\VLoginGamePacket;
use hachkingtohach1\vennv\compat\packets\VLogoutGamePacket;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInArmAnimation;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInAttackEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockDig;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockPlace;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInCloseWindow;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInDeath;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInReceivingPing;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInRotation;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInSleeping;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInSneaking;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInSteerVehicle;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInTransaction;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInUseEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInWeb;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOnElastomers;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOnIce;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOnLiquid;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOnStair;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityEffect;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityTeleport;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityVelocity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutIce;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutLiquid;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutOpenWindow;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutRespawn;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutSleeping;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutSound;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutStair;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutTransaction;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutUnderAttack;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutWeb;
use hachkingtohach1\vennv\compat\packets\VPacketSentFrequently;
use hachkingtohach1\vennv\compat\packets\VPlayerActionPacket;
use hachkingtohach1\vennv\compat\packets\VUpdateAttributesPacket;
use hachkingtohach1\vennv\data\manager\DataManager;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\PlayerAuthInputPacket;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\UpdateAttributesPacket;

trait PacketUtil{

    private function convertPacketByAPI(DataPacketSendEvent|DataPacketReceiveEvent $event) : array{
		$result = [];
		if($event instanceof DataPacketSendEvent){
			if(DataManager::getAPIServer() === 3){	
				$result[] = [$event->getPacket(), $event->getPlayer()->getName()];
			}else{
				foreach($event->getTargets() as $target){
					foreach($event->getPackets() as $packet){
						if($target->getPlayerInfo() !== null){
							$result[] = [$packet, $target->getPlayerInfo()->getUsername()];
						}
					}
				}
			}
		}
		if($event instanceof DataPacketReceiveEvent){
			if(DataManager::getAPIServer() === 3){	
				$result[] = [$event->getPacket(), $event->getPlayer()->getName()];
			}else{
				if($event->getOrigin()->getPlayerInfo() !== null){
					$result[] = [$event->getPacket(), $event->getOrigin()->getPlayerInfo()->getUsername()];
				}				
			}
		}
		return $result;
	}

	private function convertPacket(DataPacket $packet, string $origin) : void{
		if($packet instanceof AnimatePacket){
			if($packet->action === 1){
				$armAnimation = new VPacketPlayInArmAnimation();		
				$armAnimation->origin = $origin;
				$armAnimation->handle();
			}		
		}
		if($packet instanceof InventoryTransactionPacket){
			$packetOutTransaction = new VPacketPlayOutTransaction();
			$packetOutTransaction->accepted = true;
			$packetOutTransaction->windowId = $packet->requestId;
			$packetOutTransaction->windowType = $packet->trData->getTypeId();
			$packetOutTransaction->origin = $origin;
			$packetOutTransaction->handle();
			foreach($packet->trData->getActions() as $action){
				$packetInTransaction = new VPacketPlayInTransaction();
				$packetInTransaction->sourceType = $action->sourceType;
				$packetInTransaction->sourceFlags = $action->sourceFlags;
				$packetInTransaction->slot = $action->inventorySlot;
				$packetInTransaction->origin = $origin;
				$packetInTransaction->handle();
			}
			//PACKET_PLAY_IN_USE_ENTITY = 3
			if($packet->trData->getTypeId() === 3){
				$playInUseEntity = new VPacketPlayInUseEntity();
				$playInUseEntity->action = $packet->trData->getActionType();
				if(DataManager::getAPIServer() === 3){
					$playerPosition = $packet->trData->getPlayerPos();
					$clickPosition = $packet->trData->getClickPos();				
				}else{
					$playerPosition = $packet->trData->getPlayerPosition();
					$clickPosition = $packet->trData->getClickPosition();
				}
				$playInUseEntity->originX = $playerPosition->getX();
				$playInUseEntity->originY = $playerPosition->getY();
				$playInUseEntity->originZ = $playerPosition->getZ();
				$playInUseEntity->targetX = $clickPosition->getX();
				$playInUseEntity->targetY = $clickPosition->getY();
				$playInUseEntity->targetZ = $clickPosition->getZ();
				$playInUseEntity->origin = $origin;
				$playInUseEntity->handle();
			}
		}
		if($packet instanceof PlayerActionPacket){
			$actionPacket = new VPlayerActionPacket();
			$actionPacket->action = $packet->action;
			$actionPacket->face = $packet->face;
			if(DataManager::getAPIServer() === 3){
				$actionPacket->x = $packet->x;
				$actionPacket->y = $packet->y;
				$actionPacket->z = $packet->z;
				$actionPacket->resultX = $packet->resultX;
				$actionPacket->resultY = $packet->resultY;
				$actionPacket->resultZ = $packet->resultZ;
			}else{
				$actionPacket->x = $packet->blockPosition->getX();
				$actionPacket->y = $packet->blockPosition->getY();
				$actionPacket->z = $packet->blockPosition->getZ();
				$actionPacket->resultX = $packet->resultPosition->getX();
				$actionPacket->resultY = $packet->resultPosition->getY();
				$actionPacket->resultZ = $packet->resultPosition->getZ();
			}
			$actionPacket->origin = $origin;
			$actionPacket->handle();
		}	
		if($packet instanceof PlayerAuthInputPacket || $packet instanceof BatchPacket){
			$sendPacket = new VPacketSentFrequently();
			$sendPacket->packet = $packet->getName();
			$sendPacket->origin = $origin;
			$sendPacket->handle();
		}
		if($packet instanceof UpdateAttributesPacket){
			$attributesPacket = new VUpdateAttributesPacket();
			foreach($packet->entries as $entrie){
				if(DataManager::getAPIServer() === 3){
					$attributesPacket->name = $entrie->getName();
					$attributesPacket->min = $entrie->getMinValue();
					$attributesPacket->max = $entrie->getMaxValue();
					$attributesPacket->current = $entrie->getValue();
					$attributesPacket->default = $entrie->getDefaultValue();
				}else{							
					$attributesPacket->name = $entrie->getId();
					$attributesPacket->min = $entrie->getMin();
					$attributesPacket->max = $entrie->getMax();
					$attributesPacket->current = $entrie->getCurrent();
					$attributesPacket->default = $entrie->getDefault();
				}
			}
			$attributesPacket->origin = $origin;
			$attributesPacket->handle();
		}
		if($packet instanceof LevelSoundEventPacket){
			$soundPacket = new VPacketPlayOutSound();
			$soundPacket->sound = $packet->sound;
			$soundPacket->origin = $origin;
			$soundPacket->handle();
		}
	}

	public static function stringToPacket(string $data) : void{
		$explode = explode(',', $data);
		if(count($explode) > 0){
			$packetId = $explode[0];
			if($packetId == ProtocolInfo::VLOGIN_GAME_PACKET && count($explode) > 1){
				$packet = new VLoginGamePacket();
				$packet->origin = $explode[1];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VLOGOUT_GAME_PACKET && count($explode) > 1){
				$packet = new VLogoutGamePacket();
				$packet->origin = $explode[1];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_ARM_ANIMATION && count($explode) > 1){
				$packet = new VPacketPlayInArmAnimation();
				$packet->origin = $explode[1];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_BLOCK_DIG && count($explode) > 5){
				$packet = new VPacketPlayInBlockDig();
				$packet->face = (int)$explode[1];
				$packet->x = (float)$explode[2];
				$packet->y = (float)$explode[3];
				$packet->z = (float)$explode[4];
				$packet->origin = $explode[5];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_BLOCK_PLACE && count($explode) > 5){
				$packet = new VPacketPlayInBlockPlace();
				$packet->face = (int)$explode[1];
				$packet->x = (float)$explode[2];
				$packet->y = (float)$explode[3];
				$packet->z = (float)$explode[4];
				$packet->origin = $explode[5];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_CHANGE_GAME_MODE && count($explode) > 2){
				$packet = new VPacketPlayInChangeGameMode();
				$packet->gamemode = (int)$explode[1];
				$packet->origin = $explode[2];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_CLOSE_WINDOW && count($explode) > 1){
				$packet = new VPacketPlayInCloseWindow();
				$packet->origin = $explode[1];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_DEATH && count($explode) > 1){
				$packet = new VPacketPlayInDeath();
				$packet->origin = $explode[1];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_RECEIVING_PING && count($explode) > 2){
				$packet = new VPacketPlayInReceivingPing();
				$packet->ping = (int)$explode[1];
				$packet->origin = $explode[2];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_ROTATION && count($explode) > 3){
				$packet = new VPacketPlayInRotation();
				$packet->yaw = (float)$explode[1];
				$packet->pitch = (float)$explode[2];
				$packet->origin = $explode[3];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_SLEEPING && count($explode) > 6){
				$packet = new VPacketPlayInSleeping();
				$packet->x = (float)$explode[1];
				$packet->y = (float)$explode[2];
				$packet->z = (float)$explode[3];
				$packet->yaw = (float)$explode[4];
				$packet->pitch = (float)$explode[5];
				$packet->origin = $explode[6];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_SNEAKING && count($explode) > 1){
				$packet = new VPacketPlayInSneaking();
				$packet->origin = $explode[1];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_TRANSACTION && count($explode) > 4){
				$packet = new VPacketPlayInTransaction();
				$packet->sourceType = (int)$explode[1];
				$packet->sourceFlags = (int)$explode[2];
				$packet->slot = (int)$explode[3];
				$packet->origin = $explode[4];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_USE_ENTITY && count($explode) > 8){
				$packet = new VPacketPlayInUseEntity();
				$packet->action = (int)$explode[1];
				$packet->origin = $explode[2];
				$packet->originX = (float)$explode[3];
				$packet->originY = (float)$explode[4];
				$packet->originZ = (float)$explode[5];
				$packet->targetX = (float)$explode[6];
				$packet->targetY = (float)$explode[7];
				$packet->targetZ = (float)$explode[8];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_WEB && count($explode) > 6){
				$packet = new VPacketPlayInWeb();
				$packet->x = (float)$explode[1];
				$packet->y = (float)$explode[2];
				$packet->z = (float)$explode[3];
				$packet->yaw = (float)$explode[4];
				$packet->pitch = (float)$explode[5];
				$packet->origin = $explode[6];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_ON_ICE && count($explode) > 6){
				$packet = new VPacketPlayOnIce();				
				$packet->x = (float)$explode[1];
				$packet->y = (float)$explode[2];
				$packet->z = (float)$explode[3];
				$packet->yaw = (float)$explode[4];
				$packet->pitch = (float)$explode[5];
				$packet->origin = $explode[6];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_ON_LIQUID && count($explode) > 1){
				$packet = new VPacketPlayOnLiquid();
				$packet->origin = $explode[1];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_ENTITY_TELEPORT && count($explode) > 7){
				$packet = new VPacketPlayOutEntityTeleport();
				$packet->x = (float)$explode[1];
				$packet->y = (float)$explode[2];
				$packet->z = (float)$explode[3];
				$packet->yaw = (float)$explode[4];
				$packet->pitch = (float)$explode[5];
				$packet->onGround = $explode[6] == "true";
				$packet->origin = $explode[7];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_ICE && count($explode) > 6){
				$packet = new VPacketPlayOutIce();
				$packet->x = (float)$explode[1];
				$packet->y = (float)$explode[2];
				$packet->z = (float)$explode[3];
				$packet->yaw = (float)$explode[4];
				$packet->pitch = (float)$explode[5];
				$packet->origin = $explode[6];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_LIQUID && count($explode) > 6){
				$packet = new VPacketPlayOutLiquid();
				$packet->x = (float)$explode[1];
				$packet->y = (float)$explode[2];
				$packet->z = (float)$explode[3];
				$packet->yaw = (float)$explode[4];
				$packet->pitch = (float)$explode[5];
				$packet->origin = $explode[6];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_OPEN_WINDOW && count($explode) > 1){
				$packet = new VPacketPlayOutOpenWindow();
				$packet->origin = $explode[1];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_POSITION && count($explode) > 7){
				$packet = new VPacketPlayOutPosition();
				$packet->x = (float)$explode[1];
				$packet->y = (float)$explode[2];
				$packet->z = (float)$explode[3];
				$packet->yaw = (float)$explode[4];
				$packet->pitch = (float)$explode[5];
				$packet->onGround = $explode[6] == "true";
				$packet->origin = $explode[7];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_RESPAWN && count($explode) > 1){
				$packet = new VPacketPlayOutRespawn();
				$packet->origin = $explode[1];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_SLEEPING && count($explode) > 6){
				$packet = new VPacketPlayOutSleeping();
				$packet->x = (float)$explode[1];
				$packet->y = (float)$explode[2];
				$packet->z = (float)$explode[3];
				$packet->yaw = (float)$explode[4];
				$packet->pitch = (float)$explode[5];
				$packet->origin = $explode[6];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_TRANSACTION && count($explode) > 4){
				$packet = new VPacketPlayOutTransaction();
				$packet->accepted = $explode[1] == "true";
				$packet->windowId = (int)$explode[2];
				$packet->windowType = (int)$explode[3];
				$packet->origin = $explode[4];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_UNDER_ATTACK && count($explode) > 7){
				$packet = new VPacketPlayOutUnderAttack();
				$packet->originX = (float)$explode[1];
				$packet->originY = (float)$explode[2];
				$packet->originZ = (float)$explode[3];
				$packet->attackerX = (float)$explode[4];
				$packet->attackerY = (float)$explode[5];
				$packet->attackerZ = (float)$explode[6];
				$packet->origin = $explode[7];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_WEB && count($explode) > 6){
				$packet = new VPacketPlayOutWeb();
				$packet->x = (float)$explode[1];
				$packet->y = (float)$explode[2];
				$packet->z = (float)$explode[3];
				$packet->yaw = (float)$explode[4];
				$packet->pitch = (float)$explode[5];
				$packet->origin = $explode[6];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAYER_ACTION_PACKET && count($explode) > 9){
				$packet = new VPlayerActionPacket();
				$packet->face = $explode[1];
				$packet->action = (int)$explode[2];
				$packet->x = (float)$explode[3];
				$packet->y = (float)$explode[4];
				$packet->z = (float)$explode[5];
				$packet->resultX = (float)$explode[6];
				$packet->resultY = (float)$explode[7];
				$packet->resultZ = (float)$explode[8];
				$packet->origin = $explode[9];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_SENT_FREQUENTLY && count($explode) > 2){
				$packet = new VPacketSentFrequently();
				$packet->packet = $explode[1];
				$packet->origin = $explode[2];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VUPDATE_ATTRIBUTES_PACKET && count($explode) > 6){
				$packet = new VUpdateAttributesPacket();
				$packet->name = $explode[1];
				$packet->min = (float)$explode[2];
				$packet->max = (float)$explode[3];
				$packet->current = (float)$explode[4];
				$packet->default = (float)$explode[5];
				$packet->origin = $explode[6];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_ENTITY_VELOCITY && count($explode) > 4){
				$packet = new VPacketPlayOutEntityVelocity();
				$packet->deltaX = (float)$explode[1];
				$packet->deltaY = (float)$explode[2];
				$packet->deltaZ = (float)$explode[3];
				$packet->origin = $explode[4];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_SOUND && count($explode) > 2){
				$packet = new VPacketPlayOutSound();
				$packet->sound = (int)$explode[1];
				$packet->origin = $explode[2];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_STEER_VEHICLE && count($explode) > 3){
				$packet = new VPacketPlayInSteerVehicle();
				$packet->sideways = (float)$explode[1];
				$packet->forward = (float)$explode[2];
				$packet->origin = $explode[3];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_ATTACK_ENTITY && count($explode) > 7){
				$packet = new VPacketPlayInAttackEntity();
				$packet->origin = $explode[1];
				$packet->originX = $explode[2];
				$packet->originY = $explode[3];
				$packet->originZ = $explode[4];
				$packet->originYaw = $explode[5];
				$packet->originPitch = $explode[6];
				$packet->targetX = $explode[7];
				$packet->targetY = $explode[8];
				$packet->targetZ = $explode[9];
				$packet->targetYaw = $explode[10];
				$packet->targetPitch = $explode[11];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_ENTITY_EFFECT && count($explode) > 5){
				$packet = new VPacketPlayOutEntityEffect();
				$packet->origin = $explode[1];
				$packet->effectId = (int)$explode[2];
				$packet->amplifier = (int)$explode[3];
				$packet->duration = (int)$explode[4];
				$packet->flags = (int)$explode[5];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_ON_STAIR && count($explode) > 6){
				$packet = new VPacketPlayOnStair();
				$packet->x = (float)$explode[1];
				$packet->y = (float)$explode[2];
				$packet->z = (float)$explode[3];
				$packet->yaw = (float)$explode[4];
				$packet->pitch = (float)$explode[5];
				$packet->origin = $explode[6];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_STAIR && count($explode) > 6){
				$packet = new VPacketPlayOutStair();
				$packet->x = (float)$explode[1];
				$packet->y = (float)$explode[2];
				$packet->z = (float)$explode[3];
				$packet->yaw = (float)$explode[4];
				$packet->pitch = (float)$explode[5];
				$packet->origin = $explode[6];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_ON_ELASTOMERS && count($explode) > 6){
				$packet = new VPacketPlayOnElastomers();
				$packet->x = (float)$explode[1];
				$packet->y = (float)$explode[2];
				$packet->z = (float)$explode[3];
				$packet->yaw = (float)$explode[4];
				$packet->pitch = (float)$explode[5];
				$packet->origin = $explode[6];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_OUT_ELASTOMERS && count($explode) > 6){
				$packet = new VPacketPlayOnElastomers();
				$packet->x = (float)$explode[1];
				$packet->y = (float)$explode[2];
				$packet->z = (float)$explode[3];
				$packet->yaw = (float)$explode[4];
				$packet->pitch = (float)$explode[5];
				$packet->origin = $explode[6];
				$packet->handle();
			}
			if($packetId == ProtocolInfo::VPACKET_PLAY_IN_FLYING && count($explode) > 7){
				$packet = new VPacketPlayInFlying();
				$packet->x = (float)$explode[1];
				$packet->y = (float)$explode[2];
				$packet->z = (float)$explode[3];
				$packet->yaw = (float)$explode[4];
				$packet->pitch = (float)$explode[5];
				$packet->onGround = $explode[6] == "true";
				$packet->isAllowed = $explode[7] == "true";
				$packet->origin = $explode[8];
				$packet->handle();
			}
		}
	}
}