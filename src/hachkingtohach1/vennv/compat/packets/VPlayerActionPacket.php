<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VPlayerActionPacket extends VPacket{

    public const START_BREAK = 0;
	public const ABORT_BREAK = 1;
	public const STOP_BREAK = 2;
	public const GET_UPDATED_BLOCK = 3;
	public const DROP_ITEM = 4;
	public const START_SLEEPING = 5;
	public const STOP_SLEEPING = 6;
	public const RESPAWN = 7;
	public const JUMP = 8;
	public const START_SPRINT = 9;
	public const STOP_SPRINT = 10;
	public const START_SNEAK = 11;
	public const STOP_SNEAK = 12;
	public const CREATIVE_PLAYER_DESTROY_BLOCK = 13;
	public const DIMENSION_CHANGE_ACK = 14; 
	public const START_GLIDE = 15;
	public const STOP_GLIDE = 16;
	public const BUILD_DENIED = 17;
	public const CRACK_BLOCK = 18;
	public const CHANGE_SKIN = 19;
	public const SET_ENCHANTMENT_SEED = 20; 
	public const START_SWIMMING = 21;
	public const STOP_SWIMMING = 22;
	public const START_SPIN_ATTACK = 23;
	public const STOP_SPIN_ATTACK = 24;
	public const INTERACT_BLOCK = 25;
	public const PREDICT_DESTROY_BLOCK = 26;
	public const CONTINUE_DESTROY_BLOCK = 27;
	public const START_ITEM_USE_ON = 28;
	public const STOP_ITEM_USE_ON = 29;
	public const CRACK_BREAK = 18;

    public const FLAG_AXIS_POSITIVE = 1;
    public const DOWN =   0 << 1;
	public const UP =    (0 << 1) | self::FLAG_AXIS_POSITIVE;
	public const NORTH =  1 << 1;
	public const SOUTH = (1 << 1) | self::FLAG_AXIS_POSITIVE;
	public const WEST =   2 << 1;
	public const EAST =  (2 << 1) | self::FLAG_AXIS_POSITIVE;

    public int $face;
	public int $action;
    public int|float $x;
    public int|float $y;
    public int|float $z;
	public int|float|null $resultX = null;
	public int|float|null $resultY = null;
	public int|float|null $resultZ = null;
	public string $origin;

    public function getId() : int{
        return ProtocolInfo::VPACKET_PLAYER_ACTION_PACKET;
    }

    public function handle() : self{
		PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}