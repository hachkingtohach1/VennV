<?php

namespace hachkingtohach1\vennv\utils;

use hachkingtohach1\vennv\data\manager\DataManager;

class ItemUtil{

    public static function getItemId(mixed $item) : int{
        if(DataManager::getAPIServer() === 5){
            return $item->getTypeId();
        }else{
            return $item->getId();
        }
    }

    public static function getItemMeta(mixed $item) : int{
        if(DataManager::getAPIServer() === 3){
            return $item->getDamage();
        }else{
            return $item->getMeta();
        }
    }
}