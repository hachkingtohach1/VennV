<?php

namespace hachkingtohach1\vennv\utils;

use pocketmine\player\Player as PlayerPm4;
use pocketmine\Player as PlayerPm3;

final class PingUtil{

    public const BEST = 0;
    public const GOOD = 1;
    public const NORMAL = 2;
    public const OKAY = 3;
    public const BAD = 4;
    public const AWFUL = 5;
    public const UNKOWN = 6;

    public static function getPing(PlayerPm4|PlayerPm3 $player) : int|float{
        return 20;
        if($player instanceof PlayerPm4){
            return $player->getNetworkSession()->getPing();
        }
        if($player instanceof PlayerPm3){
            return $player->getPing();
        }
    }

    //https://gospeedcheck.com/article/what-is-a-good-ping-test-result-65
    public static function getPingRate(int|float $ping) :int{
        $list = [
            PingUtil::BEST => [0, 10],
            PingUtil::GOOD => [11, 19],
            PingUtil::NORMAL => [20, 50],
            PingUtil::OKAY => [51, 100],
            PingUtil::BAD => [101, 300],
            PingUtil::AWFUL => [301, PHP_INT_MAX]
        ];
        foreach($list as $rate => $range){
            if($range[0] >= $ping && $ping <= $range[1]){
                return $rate;
            }
        }
        return PingUtil::UNKOWN;
    }

    public static function getPingRateName(int $rate) :string{
        $list = [
            PingUtil::BEST => "Best",
            PingUtil::GOOD => "Good",
            PingUtil::NORMAL => "Normal",
            PingUtil::OKAY => "Okay",
            PingUtil::BAD => "Bad",
            PingUtil::AWFUL => "Awful",
            PingUtil::UNKOWN => "Unkown"
        ];
        return $list[$rate];
    }

    public static function getPingRateColor(int $rate) :string{
        $list = [
            PingUtil::BEST => "§a",
            PingUtil::GOOD => "§2",
            PingUtil::NORMAL => "§e",
            PingUtil::OKAY => "§6",
            PingUtil::BAD => "§c",
            PingUtil::AWFUL => "§4",
            PingUtil::UNKOWN => "§7"
        ];
        return $list[$rate];
    }

    public static function getPingRateColorName(int $rate) :string{
        return PingUtil::getPingRateColor($rate).PingUtil::getPingRateName($rate);
    }

    public static function getPingRateColorNamePing(int|float $ping) :string{
        return PingUtil::getPingRateColorName(PingUtil::getPingRate($ping));
    }
}