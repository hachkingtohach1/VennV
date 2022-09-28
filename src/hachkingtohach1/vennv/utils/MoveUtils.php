<?php

namespace hachkingtohach1\vennv\utils;

final class MoveUtils{

    public const MAXIMUM_PITCH = 90.0;
    public const FRICTION = 0.91;
    public const FRICTION_FACTOR = 0.6;
    public const WATER_FRICTION = 0.800000011920929;
    public const MOTION_Y_FRICTION = 0.9800000190734863;
    public const JUMP_MOTION = 1.3;
    public const LAND_GROUND_MOTION = -0.07840000152587834;
    public const JUMP_MOVEMENT_FACTOR = 0.026;
    public const BASE_AIR_SPEED = 0.3565;
    public const BASE_GROUND_SPEED = 0.2867;
    public const RESET_MOTION = 0.003016261509046103;
    public const ATTRIBUTE_SPEED = 0.1;
    public const DISTACE_JUMP = 5.2;

    public static function getBaseAirSpeed() : int{
        return 0;
    }

    public static function getBaseGroundSpeed() : int{
        return 0;
    }

    public static function getCustomSpeed() : int{
        return 0;
    }
}