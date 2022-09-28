<?php

namespace hachkingtohach1\vennv\api;

class API{

    private string $version = "2.7.5";
    private static $enabled = false;

    public static function isEnabled() : bool{
        return self::$enabled;
    }

    public static function setEnabled(bool $enabled) : void{
        self::$enabled = $enabled;
    }

    public function getVersion() : string{
        return $this->version;
    }
}