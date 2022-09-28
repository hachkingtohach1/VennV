<?php

namespace hachkingtohach1\vennv\form\impl;

use hachkingtohach1\vennv\form\FormAPI;
use hachkingtohach1\vennv\form\manager\FormManager;
use hachkingtohach1\vennv\storage\StorageEngine;
use hachkingtohach1\vennv\utils\TextFormat;
use pocketmine\Player as PlayerPm3;
use pocketmine\player\Player as PlayerPm4;

class TypeForm extends FormAPI{

    public function sendForm(PlayerPm3|PlayerPm4 $player, string $check) : void{
        $nameModule = "checks.".strtolower($check);
        $settings = StorageEngine::getInstance()->getConfig()->getData($nameModule);
        $types = [];
        foreach($settings as $key => $value){
            $key != "enable" ? $types[] = $key : null;
        }
        $choose = ["kick", "ban", "random", "nothing"];
        $form = $this->createCustomForm(function (PlayerPm3|PlayerPm4 $player, $data) use($nameModule, $types, $choose){
            FormManager::checkForm($player);
            if($data === null) return;
            StorageEngine::getInstance()->getConfig()->setData($nameModule.".enable", [false, true][$data[0]]);       
            foreach($types as $key => $value){
                $result = $data[$key + 1];
                $type = $choose[$result];
                $convert = [
                    "kick" => ["kick" => true, "ban" => false],
                    "ban" => ["kick" => false, "ban" => true],
                    "random" => ["kick" => true, "ban" => true],
                    "nothing" => ["kick" => false, "ban" => false]
                ];
                StorageEngine::getInstance()->getConfig()->setData($nameModule.".".$value.".kick", $convert[$type]["kick"]);
                StorageEngine::getInstance()->getConfig()->setData($nameModule.".".$value.".ban", $convert[$type]["ban"]);
            }
        });
        $form->setTitle(TextFormat::BOLD.$check);
        $form->addToggle("Enable this module?", true);
        foreach($settings as $key => $value){
            if($key != "enable"){
                if(is_bool($value["kick"]) && is_bool($value["ban"])){
                    $current = 3;
                    $value["kick"] && $value["ban"] ? $current = 2 : null;               
                    !$value["kick"] && $value["ban"] ? $current = 1 : null;
                    $value["kick"] && !$value["ban"] ? $current = 0 : null;
                }
                $form->addStepSlider($key, $choose, $current);
            }
        }
        $form->sendToPlayer($player);
    }
}