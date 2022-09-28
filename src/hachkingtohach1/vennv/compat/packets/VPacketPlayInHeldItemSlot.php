<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VPacketPlayInHeldItemSlot extends VPacket{

    public string $origin;
    public int $id;
    public int $meta;
    public int $slot;
    public string $name;
    public array $lore = [];

    public function getId() : int{
        return ProtocolInfo::VPACKET_PLAY_IN_HELD_ITEM_SLOT;
    }

    public function handle() : self{
        PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}