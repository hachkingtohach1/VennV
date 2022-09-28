<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VPacketSentFrequently extends VPacket{

    public string $packet;
    public string $origin;

    public function getId() : int{
        return ProtocolInfo::VPACKET_SENT_FREQUENTLY;
    }

    public function handle() : self{
        PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}