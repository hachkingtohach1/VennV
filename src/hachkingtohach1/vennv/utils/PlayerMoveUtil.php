<?php

namespace hachkingtohach1\vennv\utils;

use hachkingtohach1\vennv\compat\packets\VPacketPlayInFlying;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInRotation;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInWeb;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOnElastomers;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOnIce;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOnLiquid;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOnStair;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutElastomers;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityVelocity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutIce;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutLiquid;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutStair;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutWeb;
use hachkingtohach1\vennv\data\manager\DataManager;
use pocketmine\player\Player as PlayerPm4;
use pocketmine\Player as PlayerPm3;

final class PlayerMoveUtil{

    public static function move(PlayerPm4|PlayerPm3 $player){
        $movePacket = new VPacketPlayOutPosition();
		$movePacket->x = $player->getLocation()->getX();
		$movePacket->y = $player->getLocation()->getY();
		$movePacket->z = $player->getLocation()->getZ();
		$movePacket->yaw = $player->getLocation()->getYaw();
		$movePacket->pitch = $player->getLocation()->getPitch();	
		$movePacket->onGround = $player->isOnGround();
		$movePacket->origin = $player->getName();
		$movePacket->handle();

		if(!$player->isOnGround()){
			$flyPacket = new VPacketPlayInFlying();
			$flyPacket->x = $player->getLocation()->getX();
			$flyPacket->y = $player->getLocation()->getY();
			$flyPacket->z = $player->getLocation()->getZ();
			$flyPacket->yaw = $player->getLocation()->getYaw();
			$flyPacket->pitch = $player->getLocation()->getPitch();	
			$flyPacket->onGround = $player->isOnGround();
			$flyPacket->isAllowed = $player->getAllowFlight();
			$flyPacket->origin = $player->getName();
			$flyPacket->handle();
		}

		$data = DataManager::getPlayerData($player->getName());
		$location = $data->getLocation();
		$lastLocation = $data->getLastLocation();
		$velocityPacket = new VPacketPlayOutEntityVelocity();
		$velocityPacket->deltaX = $location->getX() - $lastLocation->getX();
		$velocityPacket->deltaY = $location->getY() - $lastLocation->getY();
		$velocityPacket->deltaZ = $location->getZ() - $lastLocation->getZ();
		$velocityPacket->origin = $player->getName();
		$velocityPacket->handle();
    }

    public static function checkBlocks(PlayerPm4|PlayerPm3 $player, bool $rotation = false){
		if(DataManager::getAPIServer() === 3){
			$onLiquid = WorldUtil::isUnderBlockPm3($player->getLocation(), BlockUtil::getLiquid(), 0) || WorldUtil::isUnderBlockPm3($player->getLocation(), BlockUtil::getLiquid(), 1);
			$inWeb = WorldUtil::isUnderBlockPm3($player->getLocation(), BlockUtil::getWeb(), 0) || WorldUtil::isUnderBlockPm3($player->getLocation(), BlockUtil::getWeb(), 1);
			$onIce = WorldUtil::isUnderBlockPm3($player->getLocation(), BlockUtil::getIce(), 0) || WorldUtil::isUnderBlockPm3($player->getLocation(), BlockUtil::getIce(), 1);
			$onStair = WorldUtil::isUnderBlockPm3($player->getLocation(), BlockUtil::getStairs(), 0) || WorldUtil::isUnderBlockPm3($player->getLocation(), BlockUtil::getStairs(), 1);
			$onElastomers = WorldUtil::isUnderBlockPm3($player->getLocation(), BlockUtil::getElastomers(), 0) || WorldUtil::isUnderBlockPm3($player->getLocation(), BlockUtil::getElastomers(), 1);
		}elseif(DataManager::getAPIServer() === 4){
			$onLiquid = WorldUtil::isUnderBlockPm4($player->getLocation(), BlockUtil::getLiquid(), 0) || WorldUtil::isUnderBlockPm4($player->getLocation(), BlockUtil::getLiquid(), 1);
			$inWeb = WorldUtil::isUnderBlockPm4($player->getLocation(), BlockUtil::getWeb(), 0) || WorldUtil::isUnderBlockPm4($player->getLocation(), BlockUtil::getWeb(), 1);
			$onIce = WorldUtil::isUnderBlockPm4($player->getLocation(), BlockUtil::getIce(), 0) || WorldUtil::isUnderBlockPm4($player->getLocation(), BlockUtil::getIce(), 1);
			$onStair = WorldUtil::isUnderBlockPm4($player->getLocation(), BlockUtil::getStairs(), 0) || WorldUtil::isUnderBlockPm4($player->getLocation(), BlockUtil::getStairs(), 1);
			$onElastomers = WorldUtil::isUnderBlockPm4($player->getLocation(), BlockUtil::getElastomers(), 0) || WorldUtil::isUnderBlockPm4($player->getLocation(), BlockUtil::getElastomers(), 1);
		}elseif(DataManager::getAPIServer() === 5){
			$onLiquid = WorldUtil::isUnderBlockPm5($player->getLocation(), BlockUtil::getLiquid(), 0) || WorldUtil::isUnderBlockPm5($player->getLocation(), BlockUtil::getLiquid(), 1);
			$inWeb = WorldUtil::isUnderBlockPm5($player->getLocation(), BlockUtil::getWeb(), 0) || WorldUtil::isUnderBlockPm5($player->getLocation(), BlockUtil::getWeb(), 1);
			$onIce = WorldUtil::isUnderBlockPm5($player->getLocation(), BlockUtil::getIce(), 0) || WorldUtil::isUnderBlockPm5($player->getLocation(), BlockUtil::getIce(), 1);
			$onStair = WorldUtil::isUnderBlockPm5($player->getLocation(), BlockUtil::getStairs(), 0) || WorldUtil::isUnderBlockPm5($player->getLocation(), BlockUtil::getStairs(), 1);
			$onElastomers = WorldUtil::isUnderBlockPm5($player->getLocation(), BlockUtil::getElastomers(), 0) || WorldUtil::isUnderBlockPm5($player->getLocation(), BlockUtil::getElastomers(), 1);
		}

		$onLiquid ? $liquidPacket = new VPacketPlayOnLiquid() : $liquidPacket = new VPacketPlayOutLiquid();
		$liquidPacket->x = $player->getLocation()->getX();
		$liquidPacket->y = $player->getLocation()->getY();
		$liquidPacket->z = $player->getLocation()->getZ();
		$liquidPacket->yaw = $player->getLocation()->getYaw();
		$liquidPacket->pitch = $player->getLocation()->getPitch();
		$liquidPacket->origin = $player->getName();
		$liquidPacket->handle();

		$inWeb ? $webPacket = new VPacketPlayInWeb() : $webPacket = new VPacketPlayOutWeb();
		$webPacket->x = $player->getLocation()->getX();
		$webPacket->y = $player->getLocation()->getY();
		$webPacket->z = $player->getLocation()->getZ();
		$webPacket->yaw = $player->getLocation()->getYaw();
		$webPacket->pitch = $player->getLocation()->getPitch();
		$webPacket->origin = $player->getName();
		$webPacket->handle();

		$onIce ? $icePacket = new VPacketPlayOnIce() : $icePacket = new VPacketPlayOutIce();
		$icePacket->x = $player->getLocation()->getX();
		$icePacket->y = $player->getLocation()->getY();
		$icePacket->z = $player->getLocation()->getZ();
		$icePacket->yaw = $player->getLocation()->getYaw();
		$icePacket->pitch = $player->getLocation()->getPitch();
		$icePacket->origin = $player->getName();
		$icePacket->handle();

		$onStair ? $stairPacket = new VPacketPlayOnStair() : $stairPacket = new VPacketPlayOutStair();
		$stairPacket->x = $player->getLocation()->getX();
		$stairPacket->y = $player->getLocation()->getY();
		$stairPacket->z = $player->getLocation()->getZ();
		$stairPacket->yaw = $player->getLocation()->getYaw();
		$stairPacket->pitch = $player->getLocation()->getPitch();
		$stairPacket->origin = $player->getName();
		$stairPacket->handle();

		$onElastomers ? $elastomersPacket = new VPacketPlayOnElastomers() : $elastomersPacket = new VPacketPlayOutElastomers();
		$elastomersPacket->x = $player->getLocation()->getX();
		$elastomersPacket->y = $player->getLocation()->getY();
		$elastomersPacket->z = $player->getLocation()->getZ();
		$elastomersPacket->yaw = $player->getLocation()->getYaw();
		$elastomersPacket->pitch = $player->getLocation()->getPitch();
		$elastomersPacket->origin = $player->getName();
		$elastomersPacket->handle();

        if($rotation){
            $rotationPacket = new VPacketPlayInRotation();
            $rotationPacket->yaw = $player->getLocation()->getYaw();
            $rotationPacket->pitch = $player->getLocation()->getPitch();
            $rotationPacket->origin = $player->getName();
			$rotationPacket->handle();
        }
    }
}