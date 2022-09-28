<?php

namespace hachkingtohach1\launcher;

use hachkingtohach1\vennv\VennVPlugin;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class VennVLauncher extends PluginBase implements Listener{

	private static $instance = null;

	public static function getPlugin() : VennVLauncher{ 
		return self::$instance; 
	}

	public function onLoad() : void{ 
		self::$instance = $this; 
	}

    public function onEnable() : void{
		$plugin = new VennVPlugin();
		$plugin->launch($this);
	}

	public function onDisable() : void{
		$plugin = new VennVPlugin();
		$plugin->shutdown($this);
	}
}