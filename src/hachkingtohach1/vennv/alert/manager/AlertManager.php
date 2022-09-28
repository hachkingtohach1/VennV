<?php

namespace hachkingtohach1\vennv\alert\manager;

use hachkingtohach1\vennv\storage\StorageEngine;
use hachkingtohach1\vennv\utils\ReplaceText;
use hachkingtohach1\vennv\utils\TextFormat;

class AlertManager{

    private const DEBUG_ALERT_MESSAGE = TextFormat::BOLD.TextFormat::BLUE."VennV".TextFormat::RESET.
            TextFormat::DARK_GRAY." >".TextFormat::WHITE."{player} ".TextFormat::DARK_GRAY."failed ".
            TextFormat::WHITE."{cheat} ".TextFormat::DARK_GRAY."VL[".TextFormat::BLUE."{vl}".TextFormat::DARK_GRAY."]";      
                
    private const DEBUG_LOG_MESSAGE = "{time} | {name} > {player} failed {cheat} VL[{vl}] {parameter}";
    
    private function uploadLogs(string $parameter, string $cheater, string $cheat, int|float $vl) : void{
        if(StorageEngine::getInstance()->getConfig()->getData(StorageEngine::ALERTS_LOGS_ENABLE) === true)
        StorageEngine::getInstance()->getLog()->contentLogger(ReplaceText::replace($this->debugAlertLog(), $cheater, $cheat, $vl, $parameter));
    }
    
    public function handleAlert(string $cheater, string $cheat, int|float $vl, string $parameter = "") : string{
        $this->uploadLogs($parameter, $cheater, $cheat, $vl);
        return ReplaceText::replace($this->debugAlert(), $cheater, $cheat, $vl);
    }

    private function debugAlertLog() : string{
        $recent = StorageEngine::getInstance()->getConfig()->getData(StorageEngine::ALERTS_LOGS_RECENT);
        if($recent === "")
        return self::DEBUG_LOG_MESSAGE;
        return $recent;
    }

    private function debugAlert() : string{
        $recent = StorageEngine::getInstance()->getConfig()->getData(StorageEngine::ALERTS_MESSAGE);
        if($recent === "")
        return self::DEBUG_ALERT_MESSAGE;
        return $recent;
    }
}