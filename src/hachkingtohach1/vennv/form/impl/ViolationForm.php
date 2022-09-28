<?php

namespace hachkingtohach1\vennv\form\impl;

use hachkingtohach1\vennv\form\FormAPI;
use hachkingtohach1\vennv\form\manager\FormManager;
use hachkingtohach1\vennv\utils\TextFormat;
use pocketmine\Player as PlayerPm3;
use pocketmine\player\Player as PlayerPm4;

class ViolationForm extends FormAPI{

    public function sendForm(PlayerPm3|PlayerPm4 $player) : void{
        $form = $this->createSimpleForm(function (PlayerPm3|PlayerPm4 $player, $data){            
            if($data === null){
                FormManager::mainForm($player);
                return;
            }
            switch($data){
                case 0:
                    (new LogsForm())->sendForm($player);
                    break;
            }
        });
        $form->setTitle(FormManager::FORM_VIOLATION_NAME);
        $checkColor = TextFormat::BLACK;
        $form->addButton($checkColor."Logs");
        $form->sendToPlayer($player);
    }
}