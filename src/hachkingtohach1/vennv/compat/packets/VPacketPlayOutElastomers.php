<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VPacketPlayOutElastomers extends VPacket{

    public int|float $x;
    public int|float $y;
    public int|float $z;
    public int|float $yaw;
    public int|float $pitch;
    public string $origin;

    public function getId() : int{
        return ProtocolInfo::VPACKET_PLAY_OUT_ELASTOMERS;
    }

    public function handle() : self{
        PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}