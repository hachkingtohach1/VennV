<?php

namespace hachkingtohach1\vennv\type;

use hachkingtohach1\vennv\check\checks\aim\AimA;
use hachkingtohach1\vennv\check\checks\aim\AimB;
use hachkingtohach1\vennv\check\checks\aim\AimC;
use hachkingtohach1\vennv\check\checks\aim\AimD;
use hachkingtohach1\vennv\check\checks\aim\AimE;
use hachkingtohach1\vennv\check\checks\aim\AimF;
use hachkingtohach1\vennv\check\checks\aim\AimG;
use hachkingtohach1\vennv\check\checks\aim\AimH;
use hachkingtohach1\vennv\check\checks\aim\AimI;
use hachkingtohach1\vennv\check\checks\aim\AimJ;
use hachkingtohach1\vennv\check\checks\aim\AimK;
use hachkingtohach1\vennv\check\checks\aim\AimL;
use hachkingtohach1\vennv\check\checks\autoclick\AutoClickA;
use hachkingtohach1\vennv\check\checks\autoclick\AutoClickB;
use hachkingtohach1\vennv\check\checks\autoclick\AutoClickC;
use hachkingtohach1\vennv\check\checks\autoclick\AutoClickD;
use hachkingtohach1\vennv\check\checks\autoclick\AutoClickE;
use hachkingtohach1\vennv\check\checks\badpackets\BadPacketsA;
use hachkingtohach1\vennv\check\checks\badpackets\BadPacketsB;
use hachkingtohach1\vennv\check\checks\badpackets\BadPacketsC;
use hachkingtohach1\vennv\check\checks\badpackets\BadPacketsD;
use hachkingtohach1\vennv\check\checks\badpackets\BadPacketsE;
use hachkingtohach1\vennv\check\checks\badpackets\BadPacketsF;
use hachkingtohach1\vennv\check\checks\badpackets\BadPacketsG;
use hachkingtohach1\vennv\check\checks\badpackets\BadPacketsH;
use hachkingtohach1\vennv\check\checks\badpackets\BadPacketsI;
use hachkingtohach1\vennv\check\checks\fly\FlyA;
use hachkingtohach1\vennv\check\checks\fly\FlyB;
use hachkingtohach1\vennv\check\checks\fly\FlyC;
use hachkingtohach1\vennv\check\checks\fly\FlyD;
use hachkingtohach1\vennv\check\checks\fly\FlyE;
use hachkingtohach1\vennv\check\checks\fly\FlyF;
use hachkingtohach1\vennv\check\checks\fly\FlyG;
use hachkingtohach1\vennv\check\checks\fly\FlyH;
use hachkingtohach1\vennv\check\checks\fly\FlyI;
use hachkingtohach1\vennv\check\checks\fly\FlyJ;
use hachkingtohach1\vennv\check\checks\fly\FlyK;
use hachkingtohach1\vennv\check\checks\fly\FlyL;
use hachkingtohach1\vennv\check\checks\hitbox\HitboxA;
use hachkingtohach1\vennv\check\checks\interact\InteractA;
use hachkingtohach1\vennv\check\checks\interact\InteractB;
use hachkingtohach1\vennv\check\checks\inventory\InventoryA;
use hachkingtohach1\vennv\check\checks\inventory\InventoryB;
use hachkingtohach1\vennv\check\checks\inventory\InventoryC;
use hachkingtohach1\vennv\check\checks\inventory\InventoryD;
use hachkingtohach1\vennv\check\checks\jesus\JesusA;
use hachkingtohach1\vennv\check\checks\jesus\JesusB;
use hachkingtohach1\vennv\check\checks\killaura\KillAuraA;
use hachkingtohach1\vennv\check\checks\killaura\KillAuraB;
use hachkingtohach1\vennv\check\checks\killaura\KillAuraC;
use hachkingtohach1\vennv\check\checks\killaura\KillAuraD;
use hachkingtohach1\vennv\check\checks\killaura\KillAuraE;
use hachkingtohach1\vennv\check\checks\killaura\KillAuraF;
use hachkingtohach1\vennv\check\checks\killaura\KillAuraG;
use hachkingtohach1\vennv\check\checks\killaura\KillAuraH;
use hachkingtohach1\vennv\check\checks\killaura\KillAuraI;
use hachkingtohach1\vennv\check\checks\killaura\KillAuraJ;
use hachkingtohach1\vennv\check\checks\killaura\KillAuraK;
use hachkingtohach1\vennv\check\checks\killaura\KillAuraL;
use hachkingtohach1\vennv\check\checks\motion\MotionA;
use hachkingtohach1\vennv\check\checks\motion\MotionB;
use hachkingtohach1\vennv\check\checks\motion\MotionC;
use hachkingtohach1\vennv\check\checks\motion\MotionD;
use hachkingtohach1\vennv\check\checks\motion\MotionF;
use hachkingtohach1\vennv\check\checks\nofall\NoFallA;
use hachkingtohach1\vennv\check\checks\reach\ReachA;
use hachkingtohach1\vennv\check\checks\reach\ReachB;
use hachkingtohach1\vennv\check\checks\reach\ReachC;
use hachkingtohach1\vennv\check\checks\reach\ReachD;
use hachkingtohach1\vennv\check\checks\reach\ReachE;
use hachkingtohach1\vennv\check\checks\reach\ReachF;
use hachkingtohach1\vennv\check\checks\scaffold\ScaffoldA;
use hachkingtohach1\vennv\check\checks\scaffold\ScaffoldB;
use hachkingtohach1\vennv\check\checks\scaffold\ScaffoldC;
use hachkingtohach1\vennv\check\checks\scaffold\ScaffoldD;
use hachkingtohach1\vennv\check\checks\speed\SpeedA;
use hachkingtohach1\vennv\check\checks\speed\SpeedB;
use hachkingtohach1\vennv\check\checks\speed\SpeedC;
use hachkingtohach1\vennv\check\checks\speed\SpeedD;
use hachkingtohach1\vennv\check\checks\speed\SpeedE;
use hachkingtohach1\vennv\check\checks\speed\SpeedF;
use hachkingtohach1\vennv\check\checks\speed\SpeedG;
use hachkingtohach1\vennv\check\checks\speed\SpeedH;
use hachkingtohach1\vennv\check\checks\timer\TimerA;
use hachkingtohach1\vennv\check\checks\timer\TimerB;
use hachkingtohach1\vennv\check\checks\timer\TimerC;
use hachkingtohach1\vennv\check\checks\timer\TimerD;
use hachkingtohach1\vennv\check\checks\velocity\VelocityA;
use hachkingtohach1\vennv\check\checks\velocity\VelocityB;
use hachkingtohach1\vennv\check\checks\velocity\VelocityC;
use hachkingtohach1\vennv\check\checks\velocity\VelocityD;
use hachkingtohach1\vennv\check\checks\velocity\VelocityE;
use hachkingtohach1\vennv\check\checks\velocity\VelocityF;
use hachkingtohach1\vennv\check\checks\velocity\VelocityG;
use hachkingtohach1\vennv\check\checks\velocity\VelocityH;
use hachkingtohach1\vennv\utils\TextFormat;

class LoadCheck{

    private static array $checkClasses = [];

    public static function getInstance() : LoadCheck{
        return new self;
    }

    public function loadChecks() : string{

        self::$checkClasses[] = new AimA;
        self::$checkClasses[] = new AimB;
        self::$checkClasses[] = new AimC;
        self::$checkClasses[] = new AimD;
        self::$checkClasses[] = new AimE;
        self::$checkClasses[] = new AimF;
        self::$checkClasses[] = new AimG;
        self::$checkClasses[] = new AimH;
        self::$checkClasses[] = new AimI;
        self::$checkClasses[] = new AimJ;
        self::$checkClasses[] = new AimK;
        self::$checkClasses[] = new AimL;

        self::$checkClasses[] = new AutoClickA;
        self::$checkClasses[] = new AutoClickB;
        self::$checkClasses[] = new AutoClickC;
        self::$checkClasses[] = new AutoClickD;
        self::$checkClasses[] = new AutoClickE;
        
        self::$checkClasses[] = new BadPacketsA;
        self::$checkClasses[] = new BadPacketsB;
        self::$checkClasses[] = new BadPacketsC;
        self::$checkClasses[] = new BadPacketsD;
        self::$checkClasses[] = new BadPacketsE;
        self::$checkClasses[] = new BadPacketsF;
        self::$checkClasses[] = new BadPacketsG;
        self::$checkClasses[] = new BadPacketsH;
        self::$checkClasses[] = new BadPacketsI;
        
        self::$checkClasses[] = new FlyA;
        self::$checkClasses[] = new FlyB;
        self::$checkClasses[] = new FlyC;
        self::$checkClasses[] = new FlyD;
        self::$checkClasses[] = new FlyE;
        self::$checkClasses[] = new FlyF;
        self::$checkClasses[] = new FlyG;
        self::$checkClasses[] = new FlyH;
        self::$checkClasses[] = new FlyI;
        self::$checkClasses[] = new FlyJ;
        self::$checkClasses[] = new FlyK;
        self::$checkClasses[] = new FlyL;

        self::$checkClasses[] = new HitboxA;

        self::$checkClasses[] = new InteractA;
        self::$checkClasses[] = new InteractB;

        self::$checkClasses[] = new InventoryA;
        self::$checkClasses[] = new InventoryB;
        self::$checkClasses[] = new InventoryC;
        self::$checkClasses[] = new InventoryD;

        self::$checkClasses[] = new JesusA;
        self::$checkClasses[] = new JesusB;

        self::$checkClasses[] = new KillAuraA;
        self::$checkClasses[] = new KillAuraB;
        self::$checkClasses[] = new KillAuraC;
        self::$checkClasses[] = new KillAuraD;
        self::$checkClasses[] = new KillAuraE;
        self::$checkClasses[] = new KillAuraF;
        self::$checkClasses[] = new KillAuraG;
        self::$checkClasses[] = new KillAuraH;
        self::$checkClasses[] = new KillAuraI;
        self::$checkClasses[] = new KillAuraJ;
        self::$checkClasses[] = new KillAuraK;
        self::$checkClasses[] = new KillAuraL;

        self::$checkClasses[] = new MotionA;
        self::$checkClasses[] = new MotionB;
        self::$checkClasses[] = new MotionC;
        self::$checkClasses[] = new MotionD;
        self::$checkClasses[] = new MotionF;

        self::$checkClasses[] = new NoFallA;

        self::$checkClasses[] = new ReachA;
        self::$checkClasses[] = new ReachB;
        self::$checkClasses[] = new ReachC;
        self::$checkClasses[] = new ReachD;
        self::$checkClasses[] = new ReachE;
        self::$checkClasses[] = new ReachF;

        self::$checkClasses[] = new ScaffoldA;
        self::$checkClasses[] = new ScaffoldB;
        self::$checkClasses[] = new ScaffoldC;
        self::$checkClasses[] = new ScaffoldD;

        self::$checkClasses[] = new SpeedA;
        self::$checkClasses[] = new SpeedB;
        self::$checkClasses[] = new SpeedC;
        self::$checkClasses[] = new SpeedD;
        self::$checkClasses[] = new SpeedE;
        self::$checkClasses[] = new SpeedF;
        self::$checkClasses[] = new SpeedG;
        self::$checkClasses[] = new SpeedH;

        self::$checkClasses[] = new TimerA;
        self::$checkClasses[] = new TimerB;
        self::$checkClasses[] = new TimerC;
        self::$checkClasses[] = new TimerD;

        self::$checkClasses[] = new VelocityA;
        self::$checkClasses[] = new VelocityB;
        self::$checkClasses[] = new VelocityC;
        self::$checkClasses[] = new VelocityD;
        self::$checkClasses[] = new VelocityE;
        self::$checkClasses[] = new VelocityF;
        self::$checkClasses[] = new VelocityG;
        self::$checkClasses[] = new VelocityH;

        $count = 0;
        foreach(self::$checkClasses as $checkClass){
            $count += $checkClass->getCloning();
        }
        
        return TextFormat::AQUA."Loaded ".TextFormat::GRAY.$count.TextFormat::AQUA." checks";
    }

    public function getCheckClasses() : array{
        return self::$checkClasses;
    }
}