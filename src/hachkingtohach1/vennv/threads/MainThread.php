<?php

namespace hachkingtohach1\vennv\threads;

use hachkingtohach1\vennv\compat\PacketManager;

class MainThread extends \Thread{

    private bool $running = false;
    private float $lastTick;

    public function run() : void{
        while($this->running){
            $this->lastTick = time() * 1000;
            require 'vendor/autoload.php';
            PacketManager::getInstance()->listenPackets();
        }            
    }

    public function start(int $options = PTHREADS_INHERIT_NONE) : bool{
        $this->running = true;
        return parent::start($options);
    }

    public function isLagging() : bool{
        return (time() * 1000) - $this->lastTick > 1000;
    }

    public function stop() : void{
        $this->running = false;
    }

    public function isRunning() : bool{
        return $this->running;
    }

    public function getTick() : float{
        return $this->lastTick;
    }
}