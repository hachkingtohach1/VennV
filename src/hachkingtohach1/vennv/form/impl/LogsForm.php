<?php

namespace hachkingtohach1\vennv\form\impl;

use hachkingtohach1\vennv\form\FormAPI;
use hachkingtohach1\vennv\form\manager\FormManager;
use hachkingtohach1\vennv\storage\StorageEngine;
use pocketmine\Player as PlayerPm3;
use pocketmine\player\Player as PlayerPm4;

class LogsForm extends FormAPI{

    public function sendForm(PlayerPm3|PlayerPm4 $player) : void{
        $form = $this->createModalForm(function (PlayerPm3|PlayerPm4 $player, $data){            
            if($data === null){
                FormManager::mainForm($player);
                return;
            }
            switch($data){
                case 0:
                    FormManager::mainForm($player);
                    break;
            }
        });
        $form->setTitle(FormManager::FORM_LOGS_NAME);
        $form->setContent(StorageEngine::getInstance()->getLog()->getLogToday());
        $form->setButton1("Back");
        $form->sendToPlayer($player);
    }
}