<?php

namespace hachkingtohach1\vennv\type;

use hachkingtohach1\launcher\VennVLauncher;
use hachkingtohach1\vennv\api\API;
use hachkingtohach1\vennv\api\events\VennVShutdownEvent;
use hachkingtohach1\vennv\api\events\VennVStartEvent;
use hachkingtohach1\vennv\command\CommandListener;
use hachkingtohach1\vennv\Server;
use hachkingtohach1\vennv\compat\VPacket;
use hachkingtohach1\vennv\data\manager\DataManager;
use hachkingtohach1\vennv\listener\DataListener;
use hachkingtohach1\vennv\listener\SetBackListener;
use hachkingtohach1\vennv\storage\StorageEngine;
use hachkingtohach1\vennv\task\ServerTickTask;
use hachkingtohach1\vennv\task\ServerTickTask2;
use hachkingtohach1\vennv\threads\MainThread;
use hachkingtohach1\vennv\utils\TextFormat;

class VennVTypeLoader implements Loader{

    private Server|null $server;
    private MainThread|null $mainThread;

    private bool $isProxy = false;
    
    private int|float $loadTime = 0;

    public static function getInstance() : Loader{
        return new self;
    }

    public function load(VennVLauncher|null $launcher = null, bool $proxy = false) : void{
        
        $api = new API();
        $api->setEnabled(true);

        StorageEngine::getInstance()->loadDataBase();
        
        $loadChecks = LoadCheck::getInstance()->loadChecks();        
        if($launcher !== null){
            StorageEngine::getInstance()->getLog()->contentLogger("VennV is loading...");
            if($this->loadTime === 0){
                $this->loadTime = microtime(true);
            }
            $event = new VennVStartEvent();
            $event->set(microtime(true));
            $event->call();
            if(!$event->isCancelled()){
                $this->loadConfig($launcher);
                $this->registerEvents($launcher);
                $this->registerTasks($launcher);
            }
            $launcher->getLogger()->info($loadChecks);
            $timeLauched = (microtime(true) - $this->loadTime) * 1000;
            $launcher->getLogger()->info(
                TextFormat::AQUA."VennV launched successfully in ".(int)$timeLauched."ms"
            );
        }
        if($proxy === true){
            $this->server = new Server();
            $this->server->run();
            $this->isProxy = true;
        }
    }

    public function isProxy() : bool{
        return $this->isProxy;
    }

    public function unload(VennVLauncher|null $launcher = null, bool $proxy = false) : void{
        $api = new API();
        $api->setEnabled(false);
        $event = new VennVShutdownEvent();
        $event->set(microtime(true));
        $event->call();
        if(!$event->isCancelled()){
            $launcher->onDisable();
        }
    }

    public function loadConfig(VennVLauncher $launcher) : void{
        $dirs = ["logs", "brain"];
        foreach($dirs as $dir){
            if(!is_dir($launcher->getDataFolder().$dir)){
                @mkdir($launcher->getDataFolder().$dir);
            }
        }
		$launcher->saveDefaultConfig();
	}

	public function registerEvents(VennVLauncher $launcher) : void{
		$launcher->getServer()->getPluginManager()->registerEvents(new DataListener(), $launcher);
        $launcher->getServer()->getPluginManager()->registerEvents(new SetBackListener(), $launcher);
        $launcher->getServer()->getPluginManager()->registerEvents(new CommandListener(), $launcher);
	}

    private function registerTasks(VennVLauncher $launcher) : void{
        if(DataManager::getAPIServer() === 3){
            $launcher->getScheduler()->scheduleRepeatingTask(new ServerTickTask2(), 1);
        }else{
            $launcher->getScheduler()->scheduleRepeatingTask(new ServerTickTask(), 1);
        }
    }

    public function check(VPacket $packet, string $origin) : void{
        foreach(LoadCheck::getInstance()->getCheckClasses() as $class){
            $class->handle($packet, $origin);
        }
    }

    public function startMainThread() : void{
        $this->mainThread = new MainThread();
        $this->mainThread->start();
    }

    public function getMainThread() : MainThread|null{
        return $this->mainThread;
    }

    public function getServer() : Server|null{
        return $this->server;
    }
}