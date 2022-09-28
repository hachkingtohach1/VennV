<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VPacketPlayInDeath extends VPacket{

    public string $origin;

    public function getId() : int{
        return ProtocolInfo::VPACKET_PLAY_IN_DEATH;
    }

    public function handle() : self{
        PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}