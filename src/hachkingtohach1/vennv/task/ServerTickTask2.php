<?php

namespace hachkingtohach1\vennv\task;

use pocketmine\scheduler\Task;

class ServerTickTask2 extends Task{

	private float $tick;
	private static $instance = null;

	public function onRun(int $currentTick){
		self::$instance = $this;
		$this->tick = microtime(true);  
	}

	public static function getInstance() : ServerTickTask2{
        return self::$instance;
    }

	public function getTick() : float{
		return $this->tick;
	}

	public function isLagging(float $l) : bool{
        $lsat = $l - $this->tick;
        return $lsat >= 5;
    }
}