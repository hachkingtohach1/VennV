<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VPacketPlayOutEntityVelocity extends VPacket{

    public int|float $deltaX;
    public int|float $deltaY;
    public int|float $deltaZ;
    public string $origin;

    public function getId() : int{
        return ProtocolInfo::VPACKET_PLAY_OUT_ENTITY_VELOCITY;
    }

    public function handle() : self{
        PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}