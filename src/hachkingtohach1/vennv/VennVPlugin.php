<?php

//http://ip-api.com/json/116.110.43.122?fields=175647

namespace hachkingtohach1\vennv;

use hachkingtohach1\launcher\VennVLaunch;
use hachkingtohach1\launcher\VennVLauncher;
use hachkingtohach1\vennv\type\VennVTypeLoader;

class VennVPlugin implements VennVLaunch{

	public const VERSION = "2.3.5";

	private static VennVLauncher|null $launcher;

    public function launch(VennVLauncher|null $launcher = null, bool $proxy = false) : void{
		self::$launcher = $launcher;
		VennVTypeLoader::getInstance()->load($launcher, $proxy);
	}

	public function shutdown(VennVLauncher|null $launcher = null, bool $proxy = false) : void{
		VennVTypeLoader::getInstance()->unload($launcher, $proxy);
	}

	public static function getPlugin() : VennVLauncher{
		return self::$launcher;
	}
}