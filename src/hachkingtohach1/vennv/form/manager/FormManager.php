<?php

namespace hachkingtohach1\vennv\form\manager;

use hachkingtohach1\vennv\form\impl\CheckForm;
use hachkingtohach1\vennv\form\impl\MainForm;
use hachkingtohach1\vennv\form\impl\ViolationForm;
use hachkingtohach1\vennv\utils\TextFormat;
use pocketmine\Player as PlayerPm3;
use pocketmine\player\Player as PlayerPm4;

class FormManager{

    public const FORM_CHECK_NAME = TextFormat::DARK_GRAY."Checks";
    public const FORM_VIOLATION_NAME = TextFormat::DARK_GRAY."Violations";
    public const FORM_LOGS_NAME = TextFormat::DARK_GRAY."Logs";
    public const FORM_MAIN_NAME = TextFormat::DARK_GRAY."VennV AntiCheat";

    public static function mainForm(PlayerPm3|PlayerPm4 $player){
        (new MainForm())->sendForm($player);
    }

    public static function checkForm(PlayerPm3|PlayerPm4 $player){
        (new CheckForm())->sendForm($player);
    }

    public static function violationForm(PlayerPm3|PlayerPm4 $player){
        (new ViolationForm())->sendForm($player);
    }
}