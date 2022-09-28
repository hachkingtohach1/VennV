<?php

namespace hachkingtohach1\vennv\utils\webhook\discord;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class WebhookSendTask extends AsyncTask{

	protected $webhook;
	protected $message;

	public function __construct(Webhook $webhook, Message $message){
		$this->webhook = $webhook;
		$this->message = $message;
	}

	public function onRun() : void{
		$ch = curl_init($this->webhook->getURL());
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->message));
		curl_setopt($ch, CURLOPT_POST,true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
		$this->setResult([curl_exec($ch), curl_getinfo($ch, CURLINFO_RESPONSE_CODE)]);
		curl_close($ch);
	}

	public function onCompletion() : void{
		$response = $this->getResult();
		if(!in_array($response[1], [200, 204])){
			Server::getInstance()->getLogger()->error("[Discord] We have error ({$response[1]}): " . $response[0]);
		}
	}
}
