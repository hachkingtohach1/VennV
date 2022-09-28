<?php

namespace hachkingtohach1\vennv\utils\webhook\discord;

use hachkingtohach1\vennv\storage\StorageEngine;

class Discord extends StorageEngine{

	public function getDiscord() : Discord{
		return new self;
	}

	public function sendBanMessage(string $text) : void{
		$discord = new Webhook($this->getConfig()->getData(self::WEBHOOK_DISCORD_BAN_URL));
		$msg = new Message();
		$msg->setUsername($this->getConfig()->getData(self::WEBHOOK_DISCORD_NAME_BOT));
		$msg->setAvatarURL($this->getConfig()->getData(self::WEBHOOK_DISCORD_AVATAR_URL));
		$msg->setContent($text); 
		$discord->send($msg);
	}

	public function sendKickMessage(string $text) : void{
		$discord = new Webhook($this->getConfig()->getData(self::WEBHOOK_DISCORD_KICK_URL));
		$msg = new Message();
		$msg->setUsername($this->getConfig()->getData(self::WEBHOOK_DISCORD_NAME_BOT));
		$msg->setAvatarURL($this->getConfig()->getData(self::WEBHOOK_DISCORD_AVATAR_URL));
		$msg->setContent($text); 
		$discord->send($msg);
	}
}
