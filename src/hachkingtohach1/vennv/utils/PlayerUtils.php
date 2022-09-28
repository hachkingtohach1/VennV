<?php

namespace hachkingtohach1\vennv\utils;

use hachkingtohach1\vennv\data\manager\PlayerData;

final class PlayerUtils{

    public static function getMaxVelocityTicks(float $velocityXZ, float $velocityY) : int|float{
        $ticks = 0;
        $horizontal = abs($velocityXZ);
        do{
            $horizontal -= .02;
            $horizontal *= MoveUtils::FRICTION;
            if($ticks++ > 30){
                break;
            }
        }while($horizontal > 0);
        $vertical = abs($velocityY);
        do{
            $vertical -= .08;
            $vertical *= MoveUtils::MOTION_Y_FRICTION;
            if($ticks++ > 60) return 0;
        }while($vertical > 0);
        return $ticks;
    }

    public static function getEyeHeight(PlayerData $data) : int|float{
        if($data->isSleeping()) return .2;
        $height = 1.62;
        if($data->isSneaking()){
            $height -= .08;
        }
        return $height;
    }

    public static function getHorizontalVelocity(float $velocityXZ) : int|float{
        return sqrt($velocityXZ * $velocityXZ);
    }

    public static function getVerticalVelocity(float $velocityY) : int|float{
        return abs($velocityY);
    }

    public static function getVelocity(float $velocityXZ, float $velocityY) : int|float{
        return sqrt($velocityXZ * $velocityXZ + $velocityY * $velocityY);
    }

    public static function getVelocityXZ(float $velocityXZ, float $velocityY) : int|float{
        return sqrt($velocityXZ * $velocityXZ + $velocityY * $velocityY);
    }

    public static function getVelocityY(float $velocityXZ, float $velocityY) : int|float{
        return sqrt($velocityXZ * $velocityXZ + $velocityY * $velocityY);
    }

    public static function getVelocityX(float $velocityXZ, float $velocityY) : int|float{
        return sqrt($velocityXZ * $velocityXZ + $velocityY * $velocityY);
    }

    public static function getVelocityZ(float $velocityXZ, float $velocityY) : int|float{
        return sqrt($velocityXZ * $velocityXZ + $velocityY * $velocityY);
    }

    public static function getVelocityXZFromYaw(float $yaw, float $velocity) : int|float{
        return -sin($yaw / 180 * M_PI) * $velocity;
    }

    public static function getVelocityXZFromPitch(float $pitch, float $velocity) : int|float{
        return -sin($pitch / 180 * M_PI) * $velocity;
    }

    public static function getVelocityYFromPitch(float $pitch, float $velocity) : int|float{
        return cos($pitch / 180 * M_PI) * $velocity;
    }

    public static function getVelocityXFromYaw(float $yaw, float $velocity) : int|float{
        return -sin($yaw / 180 * M_PI) * $velocity;
    }

    public static function getVelocityZFromYaw(float $yaw, float $velocity) : int|float{
        return cos($yaw / 180 * M_PI) * $velocity;
    }

    public static function getVelocityXFromPitch(float $pitch, float $velocity) : int|float{
        return -sin($pitch / 180 * M_PI) * $velocity;
    }

    public static function getVelocityZFromPitch(float $pitch, float $velocity) : int|float{
        return cos($pitch / 180 * M_PI) * $velocity;
    }
}