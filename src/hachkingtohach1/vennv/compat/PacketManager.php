<?php

namespace hachkingtohach1\vennv\compat;

use hachkingtohach1\vennv\compat\packets\VLoginGamePacket;
use hachkingtohach1\vennv\compat\packets\VLogoutGamePacket;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockDig;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInBlockPlace;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInChangeGameMode;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInCloseWindow;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInDeath;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInHeldItemSlot;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInReceivingPing;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInSleeping;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInSneaking;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInSteerVehicle;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInUseEntity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayInWeb;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOnElastomers;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOnIce;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOnLiquid;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOnStair;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutElastomers;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityEffect;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityTeleport;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityVelocity;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutGetOutVehicle;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutIce;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutLiquid;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutOpenWindow;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutPosition;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutRespawn;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutSleeping;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutStair;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutUnderAttack;
use hachkingtohach1\vennv\compat\packets\VPacketPlayOutWeb;
use hachkingtohach1\vennv\compat\packets\VUpdateAttributesPacket;
use hachkingtohach1\vennv\data\manager\DataManager;
use hachkingtohach1\vennv\type\VennVTypeLoader;
use hachkingtohach1\vennv\utils\AttributesUtil;

class PacketManager extends PacketHandler{

    public static function getInstance() : PacketManager{
        return new self;
    } 

    public function listenSinglePacket(VPacket $packet) : void{
        $data = DataManager::getPlayerData($packet->origin);
        if($packet instanceof VLoginGamePacket){
            $data->join($packet);
        }
        if($packet instanceof VLogoutGamePacket){
            $data->quit($packet);
        }
        if($packet instanceof VPacketPlayOutPosition){
            $data->handleMove($packet);
        }
        if($packet instanceof VPacketPlayOutEntityTeleport){
            $data->handleTeleport($packet);
        }
        if($packet instanceof VPacketPlayInSleeping){
            $data->setSleeping(true);
        }
        if($packet instanceof VPacketPlayOutSleeping){
            $data->setSleeping(false);
        }
        if($packet instanceof VPacketPlayInSneaking){
            if($data->isSneaking()){
                $data->setSneaking(false);
            }else{
                $data->setSneaking(true);
            }
        }
        if($packet instanceof VPacketPlayInDeath){
            $data->setDeathTicks(microtime(true));
        }
        if($packet instanceof VPacketPlayOutRespawn){
            $data->setRespawnTicks(microtime(true));
        }
        if($packet instanceof VUpdateAttributesPacket){
            if($packet->name === AttributesUtil::MOVEMENT){
                $data->setSpeed($packet->current);
            }
        }
        if($packet instanceof VPacketPlayOutEntityVelocity){
            $data->handleVelocity($packet);
        }
        if($packet instanceof VPacketPlayOnLiquid){
            $data->setOnLiquid(true);
        }
        if($packet instanceof VPacketPlayOutLiquid){
            $data->setOnLiquid(false);
        }
        if($packet instanceof VPacketPlayOnIce){
            $data->setOnIce(true);
        }
        if($packet instanceof VPacketPlayOutIce){
            $data->setOnIce(false);
        }
        if($packet instanceof VPacketPlayInWeb){
            $data->setOnWeb(true);
        }
        if($packet instanceof VPacketPlayOutWeb){
            $data->setOnWeb(false);
        }
        if($packet instanceof VPacketPlayInUseEntity){
            $data->handleAttack($packet);
        }
        if($packet instanceof VPacketPlayOutUnderAttack){
            $data->handleUnderAttack($packet);
        }
        if($packet instanceof VPacketPlayInChangeGameMode){
            $data->handleChangeGameMode($packet);
        }
        if($packet instanceof VPacketPlayInBlockPlace){
            $data->handlePlaceBlock($packet);
        }
        if($packet instanceof VPacketPlayInBlockDig){
            $data->handleBreakBlock($packet);
        }
        if($packet instanceof VPacketPlayOutOpenWindow){
            if($data->getJoinTicks() > 2){
                $data->handleOpenWindow($packet);
            }              
        }
        if($packet instanceof VPacketPlayInCloseWindow){
            $data->handleCloseWindow($packet);
        }
        if($packet instanceof VPacketPlayInReceivingPing){
            $data->handleReceivingPing($packet);
        }
        if($packet instanceof VPacketPlayInSteerVehicle){
            $data->handleSteerVehicle($packet);
        }
        if($packet instanceof VPacketPlayOutGetOutVehicle){
            $data->handleLeaveVehicle($packet);
        }
        if($packet instanceof VPacketPlayOutEntityEffect){
            $data->handleEffect($packet);
        }
        if($packet instanceof VPacketPlayOnStair){
            $data->setOnStair(true);
        }
        if($packet instanceof VPacketPlayOutStair){
            $data->setOnStair(false);
        }
        if(
            $packet instanceof VPacketPlayOnElastomers ||
            $packet instanceof VPacketPlayOutElastomers
        ){
            $data->handleElastomers($packet);
        }
        if($packet instanceof VPacketPlayInHeldItemSlot){
            $data->handleHeldItem($packet);
        }
        VennVTypeLoader::getInstance()->check($packet, $packet->origin);      
    }

    public function listenPackets() : void{
        foreach($this->getAll() as $id => $packet){
            $data = DataManager::getPlayerData($packet->origin);
            if($packet instanceof VLoginGamePacket){
                $data->join($packet);
            }
            if($packet instanceof VLogoutGamePacket){
                $data->quit($packet);
            }
            if($packet instanceof VPacketPlayOutPosition){
                $data->handleMove($packet);
            }
            if($packet instanceof VPacketPlayOutEntityTeleport){
                $data->handleTeleport($packet);
            }
            if($packet instanceof VPacketPlayInSleeping){
                $data->setSleeping(true);
            }
            if($packet instanceof VPacketPlayOutSleeping){
                $data->setSleeping(false);
            }
            if($packet instanceof VPacketPlayInSneaking){
                if($data->isSneaking()){
                    $data->setSneaking(false);
                }else{
                    $data->setSneaking(true);
                }
            }
            if($packet instanceof VPacketPlayInDeath){
                $data->setDeathTicks(microtime(true));
            }
            if($packet instanceof VPacketPlayOutRespawn){
                $data->setRespawnTicks(microtime(true));
            }
            if($packet instanceof VUpdateAttributesPacket){
                if($packet->name === AttributesUtil::MOVEMENT){
                    $data->setSpeed($packet->current);
                }
            }
            if($packet instanceof VPacketPlayOutEntityVelocity){
                $data->handleVelocity($packet);
            }
            if($packet instanceof VPacketPlayOnLiquid){
                $data->setOnLiquid(true);
            }
            if($packet instanceof VPacketPlayOutLiquid){
                $data->setOnLiquid(false);
            }
            if($packet instanceof VPacketPlayOnIce){
                $data->setOnIce(true);
            }
            if($packet instanceof VPacketPlayOutIce){
                $data->setOnIce(false);
            }
            if($packet instanceof VPacketPlayInWeb){
                $data->setOnWeb(true);
            }
            if($packet instanceof VPacketPlayOutWeb){
                $data->setOnWeb(false);
            }
            if($packet instanceof VPacketPlayInUseEntity){
                $data->handleAttack($packet);
            }
            if($packet instanceof VPacketPlayOutUnderAttack){
                $data->handleUnderAttack($packet);
            }
            if($packet instanceof VPacketPlayInChangeGameMode){
                $data->handleChangeGameMode($packet);
            }
            if($packet instanceof VPacketPlayInBlockPlace){
                $data->handlePlaceBlock($packet);
            }
            if($packet instanceof VPacketPlayInBlockDig){
                $data->handleBreakBlock($packet);
            }
            if($packet instanceof VPacketPlayOutOpenWindow){
                if($data->getJoinTicks() > 2){
                    $data->handleOpenWindow($packet);
                }              
            }
            if($packet instanceof VPacketPlayInCloseWindow){
                $data->handleCloseWindow($packet);
            }
            if($packet instanceof VPacketPlayInReceivingPing){
                $data->handleReceivingPing($packet);
            }
            if($packet instanceof VPacketPlayInSteerVehicle){
                $data->handleSteerVehicle($packet);
            }
            if($packet instanceof VPacketPlayOutGetOutVehicle){
                $data->handleLeaveVehicle($packet);
            }
            if($packet instanceof VPacketPlayOutEntityEffect){
                $data->handleEffect($packet);
            }
            if($packet instanceof VPacketPlayOnStair){
                $data->setOnStair(true);
            }
            if($packet instanceof VPacketPlayOutStair){
                $data->setOnStair(false);
            }
            if(
                $packet instanceof VPacketPlayOnElastomers ||
                $packet instanceof VPacketPlayOutElastomers
            ){
                $data->handleElastomers($packet);
            }
            if($packet instanceof VPacketPlayInHeldItemSlot){
                $data->handleHeldItem($packet);
            }
            VennVTypeLoader::getInstance()->check($packet, $packet->origin);
            $this->remove($id);
        }        
    }
}