<?php

namespace hachkingtohach1\vennv\check\types;

use hachkingtohach1\vennv\check\Check;
use hachkingtohach1\vennv\compat\VPacket;

abstract class PacketCheck extends Check{

    public abstract function handle(VPacket $packet, string $origin) : void;
}