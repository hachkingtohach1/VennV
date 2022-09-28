<?php

namespace hachkingtohach1\vennv\utils;

use hachkingtohach1\vennv\compat\packets\VPacketPlayOutEntityEffect;
use hachkingtohach1\vennv\data\manager\DataManager;
use pocketmine\player\Player as PlayerPm4;
use pocketmine\Player as PlayerPm3;

final class EffectUtil{

    public static function toEffectId(string $name) :int{
        $name = strtolower($name);
        if(strpos($name, "absorption") !== false){
            return VPacketPlayOutEntityEffect::ABSORPTION;
        }
        if(strpos($name, "bad_omen") !== false || strpos($name, "badomen") !== false){
            return VPacketPlayOutEntityEffect::BAD_OMEN;
        }
        if(strpos($name, "blindness") !== false){
            return VPacketPlayOutEntityEffect::BLINDNESS;
        }
        if(strpos($name, "conduit_power") !== false || strpos($name, "conduitpower") !== false){
            return VPacketPlayOutEntityEffect::CONDUIT_POWER;
        }
        if(strpos($name, "dolphins_grace") !== false || strpos($name, "dolphinsgrace") !== false){
            return VPacketPlayOutEntityEffect::DOLPHINS_GRACE;
        }
        if(strpos($name, "fire_resistance") !== false || strpos($name, "fireresistance") !== false){
            return VPacketPlayOutEntityEffect::FIRE_RESISTANCE;
        }
        if(strpos($name, "glowing") !== false){
            return VPacketPlayOutEntityEffect::GLOWING;
        }
        if(strpos($name, "haste") !== false){
            return VPacketPlayOutEntityEffect::HASTE;
        }
        if(strpos($name, "health_boost") !== false || strpos($name, "healthboost") !== false){
            return VPacketPlayOutEntityEffect::HEALTH_BOOST;
        }
        if(strpos($name, "hero_of_the_village") !== false || strpos($name, "heroofthevillage") !== false || strpos($name, "hero") !== false){
            return VPacketPlayOutEntityEffect::HERO_OF_THE_VILLAGE;
        }
        if(strpos($name, "hunger") !== false){
            return VPacketPlayOutEntityEffect::HUNGER;
        }
        if(strpos($name, "instant_damage") !== false || strpos($name, "instantdamage") !== false){
            return VPacketPlayOutEntityEffect::INSTANT_DAMAGE;
        }
        if(strpos($name, "instant_health") !== false || strpos($name, "instanthealth") !== false){
            return VPacketPlayOutEntityEffect::INSTANT_HEALTH;
        }
        if(strpos($name, "invisibility") !==  false){
            return VPacketPlayOutEntityEffect::INVISIBILITY;
        }
        if(strpos($name, "jump_boost") !== false || strpos($name, "jumpboost") !== false || strpos($name, "jump") !== false){
            return VPacketPlayOutEntityEffect::JUMP;
        }
        if(strpos($name, "levitation") !== false){
            return VPacketPlayOutEntityEffect::LEVITATION;
        }
        if(strpos($name, "luck") !== false){
            return VPacketPlayOutEntityEffect::LUCK;
        }
        if(strpos($name, "mining_fatigue") !== false || strpos($name, "miningfatigue") !== false){
            return VPacketPlayOutEntityEffect::MINING_FATIGUE;
        }
        if(strpos($name, "nausea") !== false){
            return VPacketPlayOutEntityEffect::NAUSEA;
        }
        if(strpos($name, "night_vision") !== false || strpos($name, "nightvision") !== false){
            return VPacketPlayOutEntityEffect::NIGHT_VISION;
        }
        if(strpos($name, "poison") !== false){
            return VPacketPlayOutEntityEffect::POISON;
        }
        if(strpos($name, "regeneration") !== false){
            return VPacketPlayOutEntityEffect::REGENERATION;
        }
        if(strpos($name, "resistance") !== false){
            return VPacketPlayOutEntityEffect::RESISTANCE;
        }
        if(strpos($name, "saturation") !== false){
            return VPacketPlayOutEntityEffect::SATURATION;
        }
        if(strpos($name, "slow_falling") !== false || strpos($name, "slowfalling") !== false){
            return VPacketPlayOutEntityEffect::SLOW_FALLING;
        }
        if(strpos($name, "slowness") !== false){
            return VPacketPlayOutEntityEffect::SLOWNESS;
        }
        if(strpos($name, "speed") !== false){
            return VPacketPlayOutEntityEffect::SPEED;
        }
        if(strpos($name, "strength") !== false){
            return VPacketPlayOutEntityEffect::STRENGTH;
        }
        if(strpos($name, "unluck") !== false){
            return VPacketPlayOutEntityEffect::UNLUCK;
        }
        if(strpos($name, "water_breathing") !== false || strpos($name, "waterbreathing") !== false){
            return VPacketPlayOutEntityEffect::WATER_BREATHING;
        }
        if(strpos($name, "weakness") !== false){
            return VPacketPlayOutEntityEffect::WEAKNESS;
        }
        if(strpos($name, "wither") !== false){
            return VPacketPlayOutEntityEffect::WITHER;
        }
        return 0;
    }

    public static function checkEffects(PlayerPm4|PlayerPm3 $player) : void{
        $effects = [];
        if(DataManager::getAPIServer() === 3){
            if(count($player->getEffects()) > 0){
                foreach($player->getEffects() as $effect){
                    $transtable = $effect->getType()->getName();
                    $effects[$transtable] = [$effect->getAmplifier(), $effect->getDuration()];
                }
            }
        }else{
            if(count($player->getEffects()->all()) > 0){
                foreach($player->getEffects()->all() as $effect){
                    $transtable = $effect->getType()->getName()->getText();
                    $effects[$transtable] = [$effect->getAmplifier(), $effect->getDuration()];
                }
            }
        }
        if(count($effects) > 0){
            foreach($effects as $name => $data){
                $effectPacket = new VPacketPlayOutEntityEffect();
                $effectPacket->effectId = self::toEffectId($name);
                $effectPacket->amplifier = $data[0];
                $effectPacket->duration = $data[1];
                $effectPacket->flags = VPacketPlayOutEntityEffect::MODIFY;
                $effectPacket->origin = $player->getName();
                $effectPacket->handle();
            }
        }
    }
}