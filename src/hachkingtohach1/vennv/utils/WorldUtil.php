<?php

namespace hachkingtohach1\vennv\utils;

use hachkingtohach1\vennv\data\manager\DataManager;
use pocketmine\math\Vector3;

final class WorldUtil{

    public static function isUnderBlockPm3(mixed $location, array $id, int $down) : bool{
        if(DataManager::getAPIServer() !== 3) return false;
        $posX = $location->getX();
        $posZ = $location->getZ();
        $fracX = (fmod($posX, 1.0) > 0.0) ? abs(fmod($posX, 1.0)) : (1.0 - abs(fmod($posX, 1.0)));
        $fracZ = (fmod($posZ, 1.0) > 0.0) ? abs(fmod($posZ, 1.0)) : (1.0 - abs(fmod($posZ, 1.0)));
        $blockX = $location->getX();
        $blockY = $location->getY() - $down;
        $blockZ = $location->getZ();
        $world = $location->getLevel();
        if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ))->getId(), $id)) return true;
        if($fracX < 0.3){
            if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ))->getId(), $id)) return true;
            if($fracZ < 0.3){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
            }elseif($fracZ > 0.7){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
            }
        }elseif($fracX > 0.7){
            if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ))->getId(), $id)) return true;
            if($fracZ < 0.3){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
            }elseif($fracZ > 0.7){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
            }
        }elseif($fracZ < 0.3){
            if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getId(), $id)) return true;
        }elseif($fracZ > 0.7 && in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getId(), $id)) return true;
        return false;
    }

    public static function isUnderBlockPm4(mixed $location, array $id, int $down) : bool{
        if(DataManager::getAPIServer() !== 4) return false;
        $posX = $location->getX();
        $posZ = $location->getZ();
        $fracX = (fmod($posX, 1.0) > 0.0) ? abs(fmod($posX, 1.0)) : (1.0 - abs(fmod($posX, 1.0)));
        $fracZ = (fmod($posZ, 1.0) > 0.0) ? abs(fmod($posZ, 1.0)) : (1.0 - abs(fmod($posZ, 1.0)));
        $blockX = $location->getX();
        $blockY = $location->getY() - $down;
        $blockZ = $location->getZ();
        $world = $location->getWorld();
        if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ))->getId(), $id)) return true;
        if($fracX < 0.3){
            if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ))->getId(), $id)) return true;
            if($fracZ < 0.3){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
            }elseif($fracZ > 0.7){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
            }
        }elseif($fracX > 0.7){
            if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ))->getId(), $id)) return true;
            if($fracZ < 0.3){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
            }elseif($fracZ > 0.7){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
            }
        }elseif($fracZ < 0.3){
            if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getId(), $id)) return true;
        }elseif($fracZ > 0.7 && in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getId(), $id)) return true;
        return false;
    }

    public static function isUnderBlockPm5(mixed $location, array $id, int $down) : bool{
        if(DataManager::getAPIServer() !== 5) return false;
        $posX = $location->getX();
        $posZ = $location->getZ();
        $fracX = (fmod($posX, 1.0) > 0.0) ? abs(fmod($posX, 1.0)) : (1.0 - abs(fmod($posX, 1.0)));
        $fracZ = (fmod($posZ, 1.0) > 0.0) ? abs(fmod($posZ, 1.0)) : (1.0 - abs(fmod($posZ, 1.0)));
        $blockX = $location->getX();
        $blockY = $location->getY() - $down;
        $blockZ = $location->getZ();
        $world = $location->getWorld();
        if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ))->getTypeId(), $id)) return true;
        if($fracX < 0.3){
            if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ))->getTypeId(), $id)) return true;
            if($fracZ < 0.3){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ - 1))->getTypeId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getTypeId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ - 1))->getTypeId(), $id)) return true;
            }elseif($fracZ > 0.7){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ + 1))->getTypeId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getTypeId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ + 1))->getTypeId(), $id)) return true;
            }
        }elseif($fracX > 0.7){
            if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ))->getTypeId(), $id)) return true;
            if($fracZ < 0.3){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ - 1))->getTypeId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getTypeId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ - 1))->getTypeId(), $id)) return true;
            }elseif($fracZ > 0.7){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ + 1))->getTypeId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getTypeId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ + 1))->getTypeId(), $id)) return true;
            }
        }elseif($fracZ < 0.3){
            if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getTypeId(), $id)) return true;
        }elseif($fracZ > 0.7 && in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getTypeId(), $id)) return true;
        return false;
    }
}