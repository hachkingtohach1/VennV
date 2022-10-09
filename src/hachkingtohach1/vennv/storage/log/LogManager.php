<?php

namespace hachkingtohach1\vennv\storage\log;

use hachkingtohach1\vennv\VennVPlugin;

final class LogManager{

    public function contentLogger(string $text) : void{
        $this->createLogToday();
        $today = date("Y-m-d");
        $file = fopen(VennVPlugin::getPlugin()->getDataFolder(). "logs/" . "{$today}.txt", "a+") or die("Unable to open file!");
        fwrite($file, "{$text}\n");
        fclose($file);
    }

    public function sendLogger(string $text) : void{
        VennVPlugin::getPlugin()->getLogger()->warning($text);           
        $this->contentLogger($text);
    }

    public function getLogToday() : string{
        $this->createLogToday();
        $today = date("Y-m-d");
        $file = fopen(VennVPlugin::getPlugin()->getDataFolder(). "logs/" . "{$today}.txt", "r") or die("Unable to open file!");
        $data = fread($file, filesize(VennVPlugin::getPlugin()->getDataFolder(). "logs/" . "{$today}.txt"));
        fclose($file);
        return $data;
    }

    public function createLogToday() : void{
        $today = date("Y-m-d");
        $file = fopen(VennVPlugin::getPlugin()->getDataFolder(). "logs/" . "{$today}.txt", "w") or die("Unable to open file!");
        fclose($file);
    }
}
