<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VPacketPlayOutUnderAttack extends VPacket{

    public int|float $originX;
    public int|float $originY;
    public int|float $originZ;
    public int|float $attackerX;
    public int|float $attackerY;
    public int|float $attackerZ;
    public string $origin;

    public function getId() : int{
        return ProtocolInfo::VPACKET_PLAY_OUT_UNDER_ATTACK;
    }

    public function handle() : self{
        PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}