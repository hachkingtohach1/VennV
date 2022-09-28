<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VPacketPlayInAttackEntity extends VPacket{

    public string $origin;
    public int|float $originX;
    public int|float $originY;
    public int|float $originZ;
    public int|float $originYaw;
    public int|float $originPitch;
    public int|float $targetX;
    public int|float $targetY;
    public int|float $targetZ;
    public int|float $targetYaw;
    public int|float $targetPitch;

    public function getId() : int{
        return ProtocolInfo::VPACKET_PLAY_IN_ATTACK_ENTITY;
    }

    public function handle() : self{
        PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}