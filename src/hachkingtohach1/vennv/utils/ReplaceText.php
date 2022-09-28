<?php

namespace hachkingtohach1\vennv\utils;

use hachkingtohach1\vennv\storage\StorageEngine;

final class ReplaceText{

    public static function replace(string $text, string $player = "", string $cheat = "", int|float $vl = 0, string $parameter = "", string $reason = "") : string{
        $keys = [
            "{name}",
            "{player}",
            "{cheat}",
            "{vl}",
            "{time}",
            "{parameter}",
            "{reason}"
        ];
        $replace = [
            StorageEngine::getInstance()->getConfig()->getData(StorageEngine::NAME_OR_PREFIX),
            $player,
            $cheat,
            $vl,
            date('Y-m-d H:i:s'),
            $parameter,
            $reason
        ];
        return str_replace($keys, $replace, $text);
    }
}