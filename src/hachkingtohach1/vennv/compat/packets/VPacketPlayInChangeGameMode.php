<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VPacketPlayInChangeGameMode extends VPacket{

    public const SURVIVAL = 0;
	public const CREATIVE = 1;
	public const ADVENTURE = 2;
	public const SPECTATOR = 3;
    public const UNKNOWN = 4;

    public int $gamemode;
    public string $origin;

    public function getId() : int{
        return ProtocolInfo::VPACKET_PLAY_IN_CHANGE_GAME_MODE;
    }

    public function handle() : self{
        PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}