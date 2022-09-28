<?php

namespace hachkingtohach1\vennv\compat\permission;

use pocketmine\player\Player as PlayerPm4;
use pocketmine\Player as PlayerPM3;

final class Permissions{

      public static function hasPermission(PlayerPm4|PlayerPM3 $player, string $permission) : bool{
            return $player->hasPermission($permission);
      }
}