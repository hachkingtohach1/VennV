<?php

namespace hachkingtohach1\vennv\machine;

use hachkingtohach1\vennv\data\manager\PlayerData;
use hachkingtohach1\vennv\data\transaction\effects\EffectHandler;
use hachkingtohach1\vennv\data\transaction\effects\IEffectHandler;
use hachkingtohach1\vennv\storage\StorageEngine;
use hachkingtohach1\vennv\utils\MoveUtils;

final class MachineLearning implements IMachineLearning{

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
    private int|float $maxViolation = 0;
    private int $idItemHeld = 0;
    private int $gameMode = 0;
    private IEffectHandler|null $effectHandler = null;

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

    public function setPingTicks(int|float $ticks) : void{
        $this->pingTicks = $ticks;
    }

    public function setPing(int|float $ping) : void{
        $this->ping = $ping;
    }

    public function setMaxPingTicks(int|float $ticks) : void{
        $this->maxPingTicks = $ticks;
    }

    public function setJoinTicks(int|float $ticks) : void{
        $this->joinTicks = $ticks;
    }

    public function setMoveTicks(int|float $ticks) : void{
        $this->moveTicks = $ticks;
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

    public function setMaxViolation(int|float $data) : void{
        $this->maxViolation = $data;
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

    public function getMoveTicks() : int|float{
        return microtime(true) - $this->moveTicks;
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

    public function getMaxViolation() : int|float{
        return $this->maxViolation;
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

    public function train(PlayerData $data) : string{
        $this->setHasJoined($data->getHasJoined());
        $this->setOnGround($data->getOnGround());
        $this->setSleeping($data->isSleeping());
        $this->setSneaking($data->isSneaking());
        $this->setOnLiquid($data->isOnLiquid());
        $this->setOnIce($data->isOnIce());
        $this->setOnWeb($data->isOnWeb());
        $this->setPlacingBlock($data->getPlacingBlock());
        $this->setDiggingBlock($data->getDiggingBlock());
        $this->setOpenWindow($data->getOpenWindow());
        $this->setInVehicle($data->getInVehicle());
        $this->setOnStair($data->isOnStair());
        $this->setOnElastomers($data->isOnElastomers());
        $this->setPingTicks($data->getPingTicks());
        $this->setPing($data->getPing());
        $this->setMaxPingTicks($data->getMaxPingTicks());
        $this->setJoinTicks($data->getJoinTicks());
        $this->setMoveTicks($data->getMoveTicks());
        $this->setDeltaX($data->getDeltaX());
        $this->setDeltaY($data->getDeltaY());
        $this->setDeltaZ($data->getDeltaZ());
        $this->setLastDeltaX($data->getLastDeltaX());
        $this->setLastDeltaY($data->getLastDeltaY());
        $this->setLastDeltaZ($data->getLastDeltaZ());
        $this->setDeltaYaw($data->getDeltaYaw());
        $this->setLastDeltaYaw($data->getLastDeltaYaw());
        $this->setDeltaPitch($data->getDeltaPitch());
        $this->setLastDeltaPitch($data->getLastDeltaPitch());
        $this->setDeltaXZ($data->getDeltaXZ());
        $this->setLastDeltaXZ($data->getLastDeltaXZ());
        $this->setTeleportTicks($data->getTeleportTicks());
        $this->setSpeed($data->getSpeed());
        $this->setDeathTicks($data->getDeathTicks());
        $this->setRespawnTicks($data->getRespawnTicks());
        $this->setVelocityTicks($data->getVelocityTicks());
        $this->setVerticalVelocityTicks($data->getVerticalVelocityTicks());
        $this->setHorizontalVelocityTicks($data->getHorizontalVelocityTicks());
        $this->setHorizontalSpeedTicks($data->getHorizontalSpeedTicks());
        $this->setVelocityX($data->getVelocityX());
        $this->setVelocityY($data->getVelocityY());
        $this->setVelocityZ($data->getVelocityZ());
        $this->setLastVelocityX($data->getLastVelocityX());
        $this->setLastVelocityY($data->getLastVelocityY());
        $this->setLastVelocityZ($data->getLastVelocityZ());
        $this->setAttackTicks($data->getAttackTicks());
        $this->setUnderAttackTicks($data->getUnderAttackTicks());
        $this->setPlaceBlockTicks($data->getPlaceBlockTicks());
        $this->setDigBlockTicks($data->getDigBlockTicks());
        $this->setElastomersTicks($data->getElastomersTicks());
        $this->setIdItemHeld($data->getIdItemHeld());
        $this->setGameMode($data->getGameMode());
        $this->setEffectHandler($data->getEffectHandler());
        $result = base64_encode(gzcompress(json_encode($this), 9));
        StorageEngine::getInstance()->getBrain()->save($result);
        return $result;
    }

    public function check(string $text) : IMachineLearning{
        $data = json_decode(gzuncompress(base64_decode($text)), true);
        $this->setHasJoined($data["hasJoined"]);
        $this->setOnGround($data["onGround"]);
        $this->setSleeping($data["sleeping"]);
        $this->setSneaking($data["sneaking"]);
        $this->setOnLiquid($data["onLiquid"]);
        $this->setOnIce($data["onIce"]);
        $this->setOnWeb($data["onWeb"]);
        $this->setPlacingBlock($data["placingBlock"]);
        $this->setDiggingBlock($data["diggingBlock"]);
        $this->setOpenWindow($data["openWindow"]);
        $this->setInVehicle($data["inVehicle"]);
        $this->setOnStair($data["onStair"]);
        $this->setOnElastomers($data["onElastomers"]);
        $this->setPingTicks($data["pingTicks"]);
        $this->setPing($data["ping"]);
        $this->setMaxPingTicks($data["maxPingTicks"]);
        $this->setJoinTicks($data["joinTicks"]);
        $this->setMoveTicks($data["moveTicks"]);
        $this->setDeltaX($data["deltaX"]);
        $this->setDeltaY($data["deltaY"]);
        $this->setDeltaZ($data["deltaZ"]);
        $this->setLastDeltaX($data["lastDeltaX"]);
        $this->setLastDeltaY($data["lastDeltaY"]);
        $this->setLastDeltaZ($data["lastDeltaZ"]);
        $this->setDeltaYaw($data["deltaYaw"]);
        $this->setLastDeltaYaw($data["lastDeltaYaw"]);
        $this->setDeltaPitch($data["deltaPitch"]);
        $this->setLastDeltaPitch($data["lastDeltaPitch"]);
        $this->setDeltaXZ($data["deltaXZ"]);
        $this->setLastDeltaXZ($data["lastDeltaXZ"]);
        $this->setTeleportTicks($data["teleportTicks"]);
        $this->setSpeed($data["speed"]);
        $this->setDeathTicks($data["deathTicks"]);
        $this->setRespawnTicks($data["respawnTicks"]);
        $this->setVelocityTicks($data["velocityTicks"]);
        $this->setVerticalVelocityTicks($data["verticalVelocityTicks"]);
        $this->setHorizontalVelocityTicks($data["horizontalVelocityTicks"]);
        $this->setHorizontalSpeedTicks($data["horizontalSpeedTicks"]);
        $this->setVelocityX($data["velocityX"]);
        $this->setVelocityY($data["velocityY"]);
        $this->setVelocityZ($data["velocityZ"]);
        $this->setLastVelocityX($data["lastVelocityX"]);
        $this->setLastVelocityY($data["lastVelocityY"]);
        $this->setLastVelocityZ($data["lastVelocityZ"]);
        $this->setAttackTicks($data["attackTicks"]);
        $this->setUnderAttackTicks($data["underAttackTicks"]);
        $this->setPlaceBlockTicks($data["placeBlockTicks"]);
        $this->setDigBlockTicks($data["digBlockTicks"]);
        $this->setElastomersTicks($data["elastomersTicks"]);
        $this->setIdItemHeld($data["idItemHeld"]);
        $this->setGameMode($data["gameMode"]);
        $this->setEffectHandler($data["effectHandler"]);
        return $this;
    }
}