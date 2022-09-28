<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VUpdateAttributesPacket extends VPacket{

    public string $name;
	public int|float $min;
	public int|float $max;
	public int|float $current;
	public int|float $default;
    public string $origin;

    public function getId() : int{
        return ProtocolInfo::VUPDATE_ATTRIBUTES_PACKET;
    }

    public function handle() : self{
        PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}