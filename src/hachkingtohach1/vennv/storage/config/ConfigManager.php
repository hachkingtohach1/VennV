<?php

namespace hachkingtohach1\vennv\storage\config;

use hachkingtohach1\vennv\VennVPlugin;

final class ConfigManager{

    public function getData(string $path) : mixed{
        return VennVPlugin::getPlugin()->getConfig()->getNested($path);
    }

    public function setData(string $path, $data) : void{
        VennVPlugin::getPlugin()->getConfig()->setNested($path, $data);
        VennVPlugin::getPlugin()->getConfig()->save();
    }

    public function removeData(string $path) : void{
        VennVPlugin::getPlugin()->getConfig()->removeNested($path);
        VennVPlugin::getPlugin()->getConfig()->save();
    }

    public function existsData(string $path) : bool{
        return VennVPlugin::getPlugin()->getConfig()->exists($path);
    }

    public function getAllData() : array{
        return VennVPlugin::getPlugin()->getConfig()->getAll();
    }

    public function reload() : void{
        VennVPlugin::getPlugin()->reloadConfig();
    }
}