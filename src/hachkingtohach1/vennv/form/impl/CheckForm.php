<?php

namespace hachkingtohach1\vennv\form\impl;

use hachkingtohach1\vennv\form\FormAPI;
use hachkingtohach1\vennv\form\manager\FormManager;
use hachkingtohach1\vennv\utils\TextFormat;
use pocketmine\Player as PlayerPm3;
use pocketmine\player\Player as PlayerPm4;

class CheckForm extends FormAPI{

    public function sendForm(PlayerPm3|PlayerPm4 $player) : void{
        $checks = [
            "aim", "autoclicker", "badpackets", "fly", "hitbox", 
            "interact", "inventory", "jesus", "killaura", "motion", 
            "nofall", "reach", "speed", "timer", "velocity"
        ];
        $form = $this->createSimpleForm(function (PlayerPm3|PlayerPm4 $player, $data) use($checks){            
            if($data === null){
                FormManager::mainForm($player);
                return;
            }
            (new TypeForm())->sendForm($player, $checks[$data]);
        });
        $form->setTitle(FormManager::FORM_CHECK_NAME);
        $checkColor = TextFormat::BLACK;
        foreach($checks as $check){
            $form->addButton($checkColor.$check);
        }
        $form->sendToPlayer($player);
    }
}