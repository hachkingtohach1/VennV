<?php

namespace hachkingtohach1\vennv\form\impl;

use hachkingtohach1\vennv\form\FormAPI;
use hachkingtohach1\vennv\form\manager\FormManager;
use hachkingtohach1\vennv\utils\TextFormat;
use hachkingtohach1\vennv\VennVPlugin;
use pocketmine\Player as PlayerPm3;
use pocketmine\player\Player as PlayerPm4;

class MainForm extends FormAPI{

    public function sendForm(PlayerPm3|PlayerPm4 $player) : void{
        $form = $this->createSimpleForm(function (PlayerPm3|PlayerPm4 $player, $data){
            if($data === null) return;
            switch($data){
                case 0:
                    FormManager::checkForm($player);
                    break;
                case 1:
                    FormManager::violationForm($player);
                    break;
            }
        });
        $form->setTitle(FormManager::FORM_MAIN_NAME);
        $outLine = "\n";
        $form->setContent(
            TextFormat::DARK_GRAY."Information:".$outLine.$outLine.
            TextFormat::DARK_GRAY."Type: ".TextFormat::WHITE."Premium".$outLine.
            TextFormat::DARK_GRAY."Build: ".TextFormat::WHITE.VennVPlugin::VERSION.$outLine
        );
        $checkColor = TextFormat::BLACK;
        $form->addButton($checkColor."Checks");
        $form->addButton($checkColor."Violations");
        $form->sendToPlayer($player);
    }
}