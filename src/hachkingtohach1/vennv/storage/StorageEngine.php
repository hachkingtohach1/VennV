<?php

namespace hachkingtohach1\vennv\storage;

use hachkingtohach1\vennv\compat\config\ConfigPaths;
use hachkingtohach1\vennv\storage\config\ConfigManager;
use hachkingtohach1\vennv\storage\brain\BrainManager;
use hachkingtohach1\vennv\storage\log\LogManager;
use hachkingtohach1\vennv\storage\mysql\MySQL;
use hachkingtohach1\vennv\storage\mysql\SQLite;

class StorageEngine extends ConfigPaths{

    private static MySQL|null $mysql = null;
    private static SQLite|null $sqlite = null;

    public static function getInstance() : StorageEngine{ 
		return new StorageEngine(); 
	}

    public function getConfig() : ConfigManager{
        return new ConfigManager();
    }

    public function getLog() : LogManager{
        return new LogManager();
    }

    public function getBrain() : BrainManager{
        return new BrainManager();
    }

    public function getAllDataBase() : array{
        $database = [];
        if(self::$mysql !== null){
            $database['mysql'] = self::$mysql;
        }
        if(self::$sqlite !== null){
            $database['sqlite'] = self::$sqlite;
        }
        return $database;
    }

    public function loadDataBase() : void{
        $this->getMySQL();
        $this->getSQLite();
    }

    public function getMySQL() : MySQL|null{
        if($this->getConfig()->getData(self::DATABASE_MYSQL_ENABLE) == true){
            if(empty(self::$mysql)){
                $mysql = new MySQL();
                $mysql->init();
                self::$mysql = $mysql;
            }
        }
        return self::$mysql;
    }

    public function getSQLite() : SQLite|null{
        if($this->getConfig()->getData(self::DATABASE_SQLITE_ENABLE) == true){
            if(empty(self::$sqlite)){
                $sqlite = new SQLite();
                $sqlite->init();
                self::$sqlite = $sqlite;
            }
        }
        return self::$sqlite;
    }
}