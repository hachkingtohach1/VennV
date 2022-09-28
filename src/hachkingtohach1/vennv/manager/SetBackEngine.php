<?php

namespace hachkingtohach1\vennv\manager;

final class SetBackEngine{

    private static array $handler = [];

    public static function addHandler(string $name, int $id): void{
        if(!isset(self::$handler[$name])){
            self::$handler[$name] = [];
        }
        if(!isset(self::$handler[$name][$id])){
            self::$handler[$name][$id] = 1;
        }
        self::$handler[$name][$id]++;
    }

    public static function canSetBack(string $name, int $id): bool{
        if(!empty(self::$handler[$name][$id])){
            self::$handler[$name][$id]--;
            return true;
        }
        return false;
    }
}