<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VPacketPlayOutEntityEffect extends VPacket{

    public const ABSORPTION = 22;
    public const BAD_OMEN = 24;
    public const BLINDNESS = 15;
    public const CONDUIT_POWER = 25;
    public const DOLPHINS_GRACE = 26;
    public const FIRE_RESISTANCE = 12;
    public const GLOWING = 25;
    public const HASTE = 3;
    public const HEALTH_BOOST = 21;
    public const HERO_OF_THE_VILLAGE = 27;
    public const HUNGER = 17;
    public const INSTANT_DAMAGE = 2;
    public const INSTANT_HEALTH = 1;
    public const INVISIBILITY = 14;
    public const JUMP = 8;
    public const LEVITATION = 24;
    public const LUCK = 23;
    public const MINING_FATIGUE = 4;
    public const NAUSEA = 9;
    public const NIGHT_VISION = 16;
    public const POISON = 19;
    public const REGENERATION = 10;
    public const RESISTANCE = 11;
    public const SATURATION = 23;
    public const SLOWNESS = 5;
    public const SLOW_FALLING = 28;
    public const SPEED = 1;
    public const STRENGTH = 5;
    public const UNLUCK = 22;
    public const WATER_BREATHING = 13;
    public const WEAKNESS = 18;
    public const WITHER = 20;

    public const ADD = 0;
    public const MODIFY = 1;
    public const REMOVE = 2;

    public string $origin;
    public int $effectId;
    public int|float $amplifier;
    public int|float $duration;
    public int $flags;

    public function getId() : int{
        return ProtocolInfo::VPACKET_PLAY_OUT_ENTITY_EFFECT;
    }

    public function handle() : self{
        PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}