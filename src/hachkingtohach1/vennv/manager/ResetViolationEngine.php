<?php

namespace hachkingtohach1\vennv\manager;

use hachkingtohach1\vennv\storage\StorageEngine;

final class ResetViolationEngine{
    
    private static int|float $lastTime = 0;

    public static function canReset() : bool{
        if(self::$lastTime === 0){
            self::$lastTime = microtime(true);
        }
        $time = StorageEngine::getInstance()->getConfig()->getData(StorageEngine::CHECK_SETTINGS_VIOLATION_RESET_INTERVAL) * 60;
        if(microtime(true) - self::$lastTime >= $time){
            self::$lastTime = microtime(true);
            return true;
        }
        return false;
    }
}