<?php

namespace hachkingtohach1\vennv\compat\packets;

use hachkingtohach1\vennv\utils\ProtocolInfo;
use hachkingtohach1\vennv\compat\PacketHandler;
use hachkingtohach1\vennv\compat\VPacket;

class VPacketPlayInTransaction extends VPacket{

    public const SOURCE_CONTAINER = 0;
	public const SOURCE_WORLD = 2;
	public const SOURCE_CREATIVE = 3;
	public const SOURCE_TODO = 99999;
	public const SOURCE_TYPE_CRAFTING_RESULT = -4;
	public const SOURCE_TYPE_CRAFTING_USE_INGREDIENT = -5;
	public const SOURCE_TYPE_ANVIL_RESULT = -12;
	public const SOURCE_TYPE_ANVIL_OUTPUT = -13;
	public const SOURCE_TYPE_ENCHANT_OUTPUT = -17;
	public const SOURCE_TYPE_TRADING_INPUT_1 = -20;
	public const SOURCE_TYPE_TRADING_INPUT_2 = -21;
	public const SOURCE_TYPE_TRADING_USE_INPUTS = -22;
	public const SOURCE_TYPE_TRADING_OUTPUT = -23;
	public const SOURCE_TYPE_BEACON = -24;
	public const ACTION_MAGIC_SLOT_CREATIVE_DELETE_ITEM = 0;
	public const ACTION_MAGIC_SLOT_CREATIVE_CREATE_ITEM = 1;
	public const ACTION_MAGIC_SLOT_DROP_ITEM = 0;
	public const ACTION_MAGIC_SLOT_PICKUP_ITEM = 1;

	public int $sourceType;
	public int $sourceFlags = 0;
	public int $slot;
    public string $origin;

    public function getId() : int{
        return ProtocolInfo::VPACKET_PLAY_IN_TRANSACTION;
    }

    public function handle() : self{
		PacketHandler::broadcastPackets($this, $this->origin);
        return $this;
    }
}