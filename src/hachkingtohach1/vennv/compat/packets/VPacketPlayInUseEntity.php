<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VPacketPlayInUseEntity extends VPacket{

    public const ACTION_INTERACT = 0;
	public const ACTION_ATTACK = 1;
	public const ACTION_ITEM_INTERACT = 2;

    public int $action;
    public string $origin;
    public int|float $originX;
    public int|float $originY;
    public int|float $originZ;
    public int|float $targetX;
    public int|float $targetY;
    public int|float $targetZ;

    public function getId() : int{
        return ProtocolInfo::VPACKET_PLAY_IN_USE_ENTITY;
    }

    public function handle() : self{
        PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}