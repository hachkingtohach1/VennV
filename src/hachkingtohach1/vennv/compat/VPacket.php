<?php

namespace hachkingtohach1\vennv\compat;

abstract class VPacket extends PacketHandler{

    abstract protected function getId() : int;

    abstract protected function handle() : VPacket;

    public function getName() : string{
        return (new \ReflectionClass($this))->getShortName();
    }
}