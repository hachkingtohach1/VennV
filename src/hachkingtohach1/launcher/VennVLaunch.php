<?php

namespace hachkingtohach1\launcher;

interface VennVLaunch{

	public function launch(VennVLauncher|null $launcher = null, bool $proxy = false) : void;

	public function shutdown(VennVLauncher|null $launcher = null, bool $proxy = false) : void;
}