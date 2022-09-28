<?php

namespace hachkingtohach1\vennv\utils;

use hachkingtohach1\vennv\data\manager\DataManager;
use pocketmine\event\Event;

class EventUtil{

    public static function cancel(Event $event){
        if(DataManager::getAPIServer() === 3){
            $event->setCancelled();
        }else{
            $event->cancel();
        }
    }
}