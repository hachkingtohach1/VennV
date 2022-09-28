<?php

namespace hachkingtohach1\vennv\data\manager;

use hachkingtohach1\vennv\alert\Alert;
use hachkingtohach1\vennv\compat\packets\VLoginGamePacket;
use hachkingtohach1\vennv\compat\packets\VLogoutGamePacket;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockDig;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockPlace;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInCloseWindow;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInHeldItemSlot;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInReceivingPing;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInSteerVehicle;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInUseEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOnElastomers;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutElastomers;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityEffect;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityTeleport;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityVelocity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutGetOutVehicle;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutOpenWindow;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutUnderAttack;
use hachkingtohach1\vennv\data\transaction\effects\IEffectHandler;
use hachkingtohach1\vennv\data\transaction\effects\EffectHandler;
use hachkingtohach1\vennv\utils\MathUtil;
use hachkingtohach1\vennv\utils\MoveUtils;
use hachkingtohach1\vennv\utils\Location;

class PlayerData{

    private string $name = "";
    private bool $hasJoined = false;
    private bool $onGround = false;
    private bool $isSleeping = false;
    private bool $isSneaking = false;
    private bool $isOnLiquid = false;
    private bool $isOnIce = false;
    private bool $isOnWeb = false;
    private bool $placingBlock = false;
    private bool $diggingBlock = false;
    private bool $openWindow = false;
    private bool $inVehicle = false;
    private bool $isOnStair = false;
    private bool $isOnElastomers = false;
    private int|float $pingTicks = 0;
    private int|float $ping = 0;
    private int|float $maxPingTicks = 0;
    private int|float $joinTicks = 0;
    private int|float $moveTicks = 0;
    private int|float $deltaX = 0;
    private int|float $deltaY = 0;
    private int|float $deltaZ = 0;
    private int|float $lastDeltaX = 0;
    private int|float $lastDeltaY = 0;
    private int|float $lastDeltaZ = 0;
    private int|float $deltaYaw = 0;
    private int|float $lastDeltaYaw = 0;
    private int|float $deltaPitch = 0;
    private int|float $lastDeltaPitch = 0;
    private int|float $deltaXZ = 0;
    private int|float $lastDeltaXZ = 0;
    private int|float $teleportTicks = 0;
    private int|float $speed = 0;
    private int|float $deathTicks = 0;
    private int|float $respawnTicks = 0;
    private int|float $velocityTicks = 0;
    private int|float $verticalVelocityTicks = 0;
    private int|float $horizontalVelocityTicks = 0;
    private int|float $horizontalSpeedTicks = 0;
    private int|float $velocityX = 0;
    private int|float $velocityY = 0;
    private int|float $velocityZ = 0;
    private int|float $lastVelocityX = 0;
    private int|float $lastVelocityY = 0;
    private int|float $lastVelocityZ = 0;
    private int|float $attackTicks = 0;
    private int|float $underAttackTicks = 0;
    private int|float $placeBlockTicks = 0;
    private int|float $digBlockTicks = 0;
    private int|float $elastomersTicks = 0;
    private int|float $idItemHeld = 0;
    private int $gameMode = 0;
    private IEffectHandler|null $effectHandler = null;
    private Location|null $recentTeleport = null;
    private Location|null $lastTeleport = null;
    private Location|null $location = null;
    private Location|null $lastLocation = null;
    private Location|null $lastLastLocation = null;

    public function setName(string $name) : void{
        $this->name = $name;
    }

    public function setHasJoined(bool $hasJoined) : void{
        $this->hasJoined = $hasJoined;
    }

    public function setOnGround(bool $onGround) : void{
        $this->onGround = $onGround;
    }

    public function setSleeping(bool $sleeping) : void{
        $this->isSleeping = $sleeping;
    }

    public function setSneaking(bool $sneaking) : void{
        $this->isSneaking = $sneaking;
    }

    public function setOnLiquid(bool $on) : void{
        $this->isOnLiquid = $on;
    }

    public function setOnIce(bool $on) : void{
        $this->isOnIce = $on;
    }

    public function setOnWeb(bool $on) : void{
        $this->isOnWeb = $on;
    }

    public function setPlacingBlock(bool $place) : void{
        $this->placingBlock = $place;
    }

    public function setDiggingBlock(bool $dig) : void{
        $this->diggingBlock = $dig;
    }

    public function setOpenWindow(bool $window) : void{
        $this->openWindow = $window;
    }

    public function setInVehicle(bool $vehicle) : void{
        $this->inVehicle = $vehicle;
    }

    public function setOnStair(bool $stair) : void{
        $this->isOnStair = $stair;
    }

    public function setOnElastomers(bool $elastomers) : void{
        $this->isOnElastomers = $elastomers;
    }

    private function setPingTicks(int|float $ticks) : void{
        $this->pingTicks = $ticks;
    }

    private function setPing(int|float $ping) : void{
        $this->ping = $ping;
    }

    private function setMaxPingTicks(int|float $ticks) : void{
        $this->maxPingTicks = $ticks;
    }

    public function setJoinTicks(int|float $ticks) : void{
        $this->joinTicks = $ticks;
    }

    public function setLocation(int|float $x, int|float $y, int|float $z, int|float $yaw, int|float $pitch, bool $onGround) : void{
        $location = new Location();
        $location->set($x, $y, $z, $yaw, $pitch, $onGround);
        $this->location = $location;
    }

    public function setMoveTicks(int|float $ticks) : void{
        $this->moveTicks = $ticks;
    }

    public function setLastLocation(Location $location) : void{
        $this->lastLocation = $location;
    }

    public function setLastLastLocation(Location $location) : void{
        $this->lastLastLocation = $location;
    }

    public function setDeltaX(int|float $data) : void{
        $this->deltaX = $data;
    }

    public function setDeltaY(int|float $data) : void{
        $this->deltaY = $data;
    }

    public function setDeltaZ(int|float $data) : void{
        $this->deltaZ = $data;
    }

    public function setLastDeltaX(int|float $data) : void{
        $this->lastDeltaX = $data;
    }

    public function setLastDeltaY(int|float $data) : void{
        $this->lastDeltaY = $data;
    }

    public function setLastDeltaZ(int|float $data) : void{
        $this->lastDeltaZ = $data;
    }

    public function setDeltaYaw(int|float $data) : void{
        $this->deltaYaw = $data;
    }

    public function setLastDeltaYaw(int|float $data) : void{
        $this->lastDeltaYaw = $data;
    }

    public function setDeltaPitch(int|float $data) : void{
        $this->deltaPitch = $data;
    }

    public function setLastDeltaPitch(int|float $data) : void{
        $this->lastDeltaPitch = $data;
    }

    public function setDeltaXZ(int|float $data) : void{
        $this->deltaXZ = $data;
    }

    public function setLastDeltaXZ(int|float $data) : void{
        $this->lastDeltaXZ = $data;
    }

    public function setTeleportTicks(int|float $data) : void{
        $this->teleportTicks = $data;
    }

    public function setRecentTeleport(Location $location) : void{
        $this->recentTeleport = $location;
    }

    public function setLastTeleport(Location $location) : void{
        $this->lastTeleport = $location;
    }

    public function setSpeed(int|float $speed) : void{
        $this->speed = $speed;
    }

    public function setDeathTicks(int|float $tick) : void{
        $this->deathTicks = $tick;
    }

    public function setRespawnTicks(int|float $tick) : void{
        $this->respawnTicks = $tick;
    }

    public function setVelocityTicks(int|float $tick) : void{
        $this->velocityTicks = $tick;
    }

    public function setVerticalVelocityTicks(int|float $tick) : void{
        $this->verticalVelocityTicks = $tick;
    }

    public function setHorizontalVelocityTicks(int|float $tick) : void{
        $this->horizontalVelocityTicks = $tick;
    }

    public function setHorizontalSpeedTicks(int|float $tick) : void{
        $this->horizontalSpeedTicks = $tick;
    }

    public function setVelocityX(int|float $data) : void{
        $this->velocityX = $data;
    }

    public function setVelocityY(int|float $data) : void{
        $this->velocityY = $data;
    }

    public function setVelocityZ(int|float $data) : void{
        $this->velocityZ = $data;
    }

    public function setLastVelocityX(int|float $data) : void{
        $this->lastVelocityX = $data;
    }

    public function setLastVelocityY(int|float $data) : void{
        $this->lastVelocityY = $data;
    }

    public function setLastVelocityZ(int|float $data) : void{
        $this->lastVelocityZ = $data;
    }

    public function setAttackTicks(int|float $tick) : void{
        $this->attackTicks = $tick;
    }

    public function setUnderAttackTicks(int|float $data) : void{
        $this->underAttackTicks = $data;
    }

    public function setPlaceBlockTicks(int|float $data) : void{
        $this->placeBlockTicks = $data;
    }

    public function setDigBlockTicks(int|float $data) : void{
        $this->digBlockTicks = $data;
    }

    public function setElastomersTicks(int|float $data) : void{
        $this->elastomersTicks = $data;
    }

    public function setIdItemHeld(int $id) : void{
        $this->idItemHeld = $id;
    }

    public function setGameMode(int $data) : void{
        $this->gameMode = $data;
    }

    public function setEffectHandler(EffectHandler $handler) : void{
        $this->effectHandler = $handler;
    }
    
    //////////////////////////////////////////////////////////////////////////

    public function getName() : string{
        return $this->name;
    }

    public function getHasJoined() : bool{
        return $this->hasJoined;
    }

    public function getOnGround() : bool{
        return $this->onGround;
    }

    public function isSleeping() : bool{
        return $this->isSleeping;
    }

    public function isSneaking() : bool{
        return $this->isSneaking;
    }

    public function isOnLiquid() : bool{
        return $this->isOnLiquid;
    }

    public function isOnIce() : bool{
        return $this->isOnIce;
    }

    public function isOnWeb() : bool{
        return $this->isOnWeb;
    }

    public function getPlacingBlock() : bool{
        return $this->placingBlock;
    }

    public function getDiggingBlock() : bool{
        return $this->diggingBlock;
    }

    public function getOpenWindow() : bool{
        return $this->openWindow;
    }

    public function getInVehicle() : bool{
        return $this->inVehicle;
    }

    public function isOnStair() : bool{
        return $this->isOnStair;
    }

    public function isOnElastomers() : bool{
        return $this->isOnElastomers;
    }

    public function getPingTicks() : int|float{
        return microtime(true) - $this->pingTicks;
    }

    public function getPing() : int{
        return (int)$this->ping;
    }

    public function getMaxPingTicks() : int|float{
        return microtime(true) - $this->maxPingTicks;
    }

    public function getJoinTicks() : int|float{
        if($this->joinTicks != 0){
            return microtime(true) - $this->joinTicks;
        }        
        return 0;
    }

    public function getLocation() : Location{
        $location = new Location();
        $location->set(0, 0, 0, 0, 0);
        return $this->location === null ? $location : $this->location;
    }

    public function getMoveTicks() : int|float{
        return microtime(true) - $this->moveTicks;
    }

    public function getLastLocation() : Location{
        $location = new Location();
        $location->set(0, 0, 0, 0, 0);
        return $this->lastLocation === null ? $location : $this->lastLocation;
    }

    public function getLastLastLocation() : Location{
        $location = new Location();
        $location->set(0, 0, 0, 0, 0);
        return $this->lastLastLocation === null ? $location : $this->lastLastLocation;
    }

    public function getDeltaX() : int|float{
        return $this->deltaX;
    }

    public function getDeltaY() : int|float{
        return $this->deltaY;
    }

    public function getDeltaZ() : int|float{
        return $this->deltaZ;
    }

    public function getLastDeltaX() : int|float{
        return $this->lastDeltaX;
    }

    public function getLastDeltaY() : int|float{
        return $this->lastDeltaY;
    }

    public function getLastDeltaZ() : int|float{
        return $this->lastDeltaZ;
    }

    public function getDeltaYaw() : int|float{
        return $this->deltaYaw;
    }

    public function getLastDeltaYaw() : int|float{
        return $this->lastDeltaYaw;
    }

    public function getDeltaPitch() : int|float{
        return $this->deltaPitch;
    }

    public function getLastDeltaPitch() : int|float{
        return $this->lastDeltaPitch;
    }

    public function getDeltaXZ() : int|float{
        return $this->deltaXZ;
    }

    public function getLastDeltaXZ() : int|float{
        return $this->lastDeltaXZ;
    }

    public function getTeleportTicks() : int|float{
        return microtime(true) - $this->teleportTicks;
    }

    public function getRecentTeleport() : Location{
        $location = new Location();
        $location->set(0, 0, 0, 0, 0);
        return $this->recentTeleport === null ? $location : $this->recentTeleport;
    }

    public function getLastTeleport() : Location{
        $location = new Location();
        $location->set(0, 0, 0, 0, 0);
        return $this->lastTeleport === null ? $location : $this->lastTeleport;
    }

    public function getSpeed() : int|float{
        if($this->speed == 0){
            return MoveUtils::ATTRIBUTE_SPEED;
        }
        return $this->speed;
    }

    public function getDeathTicks() : int|float{
        return microtime(true) - $this->deathTicks;
    }

    public function getRespawnTicks() : int|float{
        return microtime(true) - $this->respawnTicks;
    }

    public function getVelocityTicks() : int|float{
        return microtime(true) - $this->velocityTicks;
    }

    public function getVerticalVelocityTicks() : int|float{
        return microtime(true) - $this->verticalVelocityTicks;
    }

    public function getHorizontalVelocityTicks() : int|float{
        return microtime(true) - $this->horizontalVelocityTicks;
    }

    public function getHorizontalSpeedTicks() : int|float{
        return microtime(true) - $this->horizontalSpeedTicks;
    }

    public function getVelocityX() : int|float{
        return $this->velocityX;
    }

    public function getVelocityY() : int|float{
        return $this->velocityY;
    }

    public function getVelocityZ() : int|float{
        return $this->velocityZ;
    }

    public function getLastVelocityX() : int|float{
        return $this->lastVelocityX;
    }

    public function getLastVelocityY() : int|float{
        return $this->lastVelocityY;
    }

    public function getLastVelocityZ() : int|float{
        return $this->lastVelocityZ;
    }

    public function getAttackTicks() : int|float{
        return microtime(true) - $this->attackTicks;
    }

    public function getUnderAttackTicks() : int|float{
        return microtime(true) - $this->underAttackTicks;
    }

    public function getPlaceBlockTicks() : int|float{
        return microtime(true) - $this->placeBlockTicks;
    }

    public function getDigBlockTicks() : int|float{
        return microtime(true) - $this->digBlockTicks;
    }

    public function getElastomersTicks() : int|float{
        return microtime(true) - $this->elastomersTicks;
    }

    public function getIdItemHeld() : int{
        return $this->idItemHeld;
    }

    public function getGameMode() : int{
        return $this->gameMode;
    }

    public function getEffectHandler() : IEffectHandler{
        if($this->effectHandler == null){
            $this->effectHandler = new EffectHandler();
        }
        return $this->effectHandler;
    }

    //////////////////////////////////////////////////////////////////////////

    public function join(VLoginGamePacket $packet) : void{
        $data = new PlayerData();
        $data->setName($packet->origin);
        $data->setHasJoined(true);
        //Just is debug
        $data->setJoinTicks(microtime(true));
        $data->setDeathTicks(microtime(true));
        $data->setRespawnTicks(microtime(true));
        $data->setVelocityTicks(microtime(true));
        $data->setVerticalVelocityTicks(microtime(true));
        $data->setHorizontalVelocityTicks(microtime(true));
        $data->setHorizontalSpeedTicks(microtime(true));
        $data->setAttackTicks(microtime(true));
        $data->setUnderAttackTicks(microtime(true));
        $data->setPlaceBlockTicks(microtime(true));
        $data->setDigBlockTicks(microtime(true));
        $data->setElastomersTicks(microtime(true));
        DataManager::inject($packet->origin, $data);
    }

    public function quit(VLogoutGamePacket $packet) : void{
        Alert::getInstance()->removeHandle($packet->origin);
        DataManager::uninject($packet->origin);
    }

    public function handleMove(VPacketPlayOutPosition $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        if($data->getJoinTicks() == 0){
            $data->setJoinTicks(microtime(true)); //Just this debug
        }
        if($data->getHasJoined() == false){
            $data->setHasJoined(true); //Just this debug
        }
        $data->setMoveTicks(microtime(true));
        $data->setPingTicks(microtime(true));
        $data->setPing($data->getPingTicks() * 500000);
        $data->setLastLastLocation($data->getLastLocation()); 
        $data->setLastLocation($data->getLocation());      
        $data->setLocation($packet->x, $packet->y, $packet->z, $packet->yaw, $packet->pitch, $packet->onGround);
        $data->setOnGround($packet->onGround);
        $location = $data->getLocation();
        $lastLocation = $data->getLastLocation();
        $data->setLastDeltaX($data->getDeltaX());
        $data->setLastDeltaY($data->getDeltaY());
        $data->setLastDeltaZ($data->getDeltaZ());
        $data->setDeltaX($location->getX() - $lastLocation->getX());
        $data->setDeltaY($location->getY() - $lastLocation->getY());
        $data->setDeltaZ($location->getZ() - $lastLocation->getZ());
        $data->setLastDeltaYaw($data->getDeltaYaw());
        $data->setDeltaYaw(abs(MathUtil::clamp180(abs($location->getYaw() - $lastLocation->getYaw()))));
        $data->setLastDeltaPitch($data->getDeltaPitch());
        $data->setDeltaPitch(abs($location->getPitch() - $lastLocation->getPitch()));
        $data->setLastDeltaXZ($data->getDeltaXZ());
        $data->setDeltaXZ(hypot($data->getDeltaX(), $data->getDeltaZ()));
    }

    public function handleTeleport(VPacketPlayOutEntityTeleport $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        $data->setTeleportTicks(microtime(true));
        $data->setOnGround($packet->onGround);
        $data->setLastTeleport($data->getRecentTeleport());
        $location = new Location();
        $location->set($packet->x, $packet->y, $packet->z, $packet->yaw, $packet->pitch, $packet->onGround);
        $data->setRecentTeleport($location);
    }

    public function handleVelocity(VPacketPlayOutEntityVelocity $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        $data->setVelocityTicks(microtime(true));
        $data->setLastVelocityX($data->getVelocityX());
        $data->setLastVelocityY($data->getVelocityY());
        $data->setLastVelocityZ($data->getVelocityZ());
        $data->setVelocityX(MoveUtils::FRICTION * ($packet->deltaX) - 0.08);
        $data->setVelocityY(MoveUtils::MOTION_Y_FRICTION * ($packet->deltaY) - 0.08);
        $data->setVelocityZ(MoveUtils::FRICTION * ($packet->deltaZ) - 0.08);
        if($packet->deltaY !== $data->getLastDeltaY()){
            $data->setVerticalVelocityTicks(microtime(true));
        }
        if(
            $packet->deltaX !== $data->getLastDeltaX() &&
            $packet->deltaZ !== $data->getLastDeltaZ()         
        ){
            $data->setHorizontalVelocityTicks(microtime(true));
        }
    }

    public function handleAttack(VPacketPlayInUseEntity $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        $data->setAttackTicks(microtime(true));
    }

    public function handleUnderAttack(VPacketPlayOutUnderAttack $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        $data->setUnderAttackTicks(microtime(true));
    }

    public function handleChangeGameMode(VPacketPlayInChangeGameMode $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        switch($packet->gamemode){
            case $packet::SURVIVAL:
                $result = $packet::SURVIVAL;
                break;
            case $packet::CREATIVE:
                $result = $packet::CREATIVE;
                break;
            case $packet::ADVENTURE:
                $result = $packet::ADVENTURE;
                break;
            case $packet::SPECTATOR:
                $result = $packet::SPECTATOR;
                break;
            default: $result = $packet::UNKNOWN;
        }
        $data->setGameMode($result);
    }

    public function handlePlaceBlock(VPacketPlayInBlockPlace $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        $data->setPlaceBlockTicks(microtime(true));
        $data->setPlacingBlock(true);
    }

    public function handleBreakBlock(VPacketPlayInBlockDig $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        $data->setDigBlockTicks(microtime(true));
        $data->setDiggingBlock(true);
    }

    public function handleOpenWindow(VPacketPlayOutOpenWindow $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        $data->setOpenWindow(true);
    }

    public function handleCloseWindow(VPacketPlayInCloseWindow $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        $data->setOpenWindow(false);
    }

    public function handleReceivingPing(VPacketPlayInReceivingPing $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        $data->setPing($packet->ping);
        $time = microtime(true);
        $pingTicks = $data->getPingTicks();
        $data->setPingTicks($time);
        if($pingTicks > $data->getMaxPingTicks()){
            $data->setMaxPingTicks($time);
        }
    }

    public function handleSteerVehicle(VPacketPlayInSteerVehicle $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        $data->setInVehicle(true);
    }

    public function handleLeaveVehicle(VPacketPlayOutGetOutVehicle $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        $data->setInVehicle(false);
    }

    public function handleEffect(VPacketPlayOutEntityEffect $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        $effect = $data->getEffectHandler();
        if($effect !== null && $packet->flags === VPacketPlayOutEntityEffect::REMOVE){
            $effect->removeEffect($packet->effectId);
        }
        if($effect !== null && $packet->flags === VPacketPlayOutEntityEffect::ADD){
            $effect->handleEffect($packet->effectId, $packet->amplifier, $packet->duration);
        }
        if($effect !== null && $packet->flags === VPacketPlayOutEntityEffect::MODIFY){
            $effect->handleEffect($packet->effectId, $packet->amplifier, $packet->duration);
        }
    }

    public function handleElastomers(VPacketPlayOnElastomers|VPacketPlayOutElastomers $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        if($packet instanceof VPacketPlayOnElastomers){
            $data->setElastomersTicks(microtime(true));
            $data->setOnElastomers(true);
        }
        if($packet instanceof VPacketPlayOutElastomers){
            $data->setOnElastomers(false);
        }
    }

    public function handleHeldItem(VPacketPlayInHeldItemSlot $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        $data->setIdItemHeld($packet->id);
    }
}