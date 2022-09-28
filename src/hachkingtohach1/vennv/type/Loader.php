<?php

namespace hachkingtohach1\vennv\type;

use hachkingtohach1\launcher\VennVLauncher;
use hachkingtohach1\vennv\compat\VPacket;

interface Loader{

    public function load(VennVLauncher|null $launcher = null, bool $proxy = false) :void;

    public function isProxy() : bool;

    public function unload(VennVLauncher|null $launcher = null, bool $proxy = false) : void;

    public function check(VPacket $packet, string $origin) : void;
}