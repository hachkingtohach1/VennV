<?php

namespace hachkingtohach1\vennv\form;

use pocketmine\form\Form as IForm;
use pocketmine\Player as PlayerPm3;
use pocketmine\player\Player as PlayerPm4;

abstract class Form implements IForm{

    protected array $data = [];
    private $callable;

    public function __construct(?callable $callable){
        $this->callable = $callable;
    }

    public function sendToPlayer(PlayerPm3|PlayerPm4 $player) : void{
        $player->sendForm($this);
    }

    public function getCallable() : ?callable {
        return $this->callable;
    }

    public function setCallable(?callable $callable) : void{
        $this->callable = $callable;
    }

    public function handleResponse(PlayerPm3|PlayerPm4 $player, $data) : void{
        $this->processData($data);
        $callable = $this->getCallable();
        if($callable !== null) {
            $callable($player, $data);
        }
    }

    public function processData(&$data) : void{}

    public function jsonSerialize() : mixed{
        return $this->data;
    }
}
