<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VPacketPlayOutGetOutVehicle extends VPacket{

    public int|float $strafe;
    public int|float $forward;

    public function getId() : int{
        return ProtocolInfo::VPACKET_PLAY_OUT_GET_OUT_VEHICLE;
    }

    public function handle() : self{
        PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}