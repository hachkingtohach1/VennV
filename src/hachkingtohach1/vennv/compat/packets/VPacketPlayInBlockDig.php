<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VPacketPlayInBlockDig extends VPacket{

    public const FLAG_AXIS_POSITIVE = 1;
    public const DOWN =   0 << 1;
	public const UP =    (0 << 1) | self::FLAG_AXIS_POSITIVE;
	public const NORTH =  1 << 1;
	public const SOUTH = (1 << 1) | self::FLAG_AXIS_POSITIVE;
	public const WEST =   2 << 1;
	public const EAST =  (2 << 1) | self::FLAG_AXIS_POSITIVE;

    public int $face;
    public int|float $x;
    public int|float $y;
    public int|float $z;
    public string $origin;

    public function getId() : int{
        return ProtocolInfo::VPACKET_PLAY_IN_BLOCK_DIG;
    }

    public function handle() : self{
        PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}