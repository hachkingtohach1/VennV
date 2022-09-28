<?php

namespace hachkingtohach1\vennv\storage\mysql;

use hachkingtohach1\vennv\storage\StorageEngine;

class SQLite extends StorageEngine{

    private bool $enable = false;
    private \PDO $db;

    public function init() : void{
        $this->db = new \PDO("sqlite:plugins/VennV/data/vennvac.db");
        if($this->db->errorCode() != 0){
            echo "Can't connect to SQLite server: ".$this->db->errorInfo();
            return;
        }
        if(!$this->db->query("
            CREATE TABLE if NOT EXISTS vennvac(anticheat VARCHAR(50) PRIMARY KEY, haspunished LONGTEXT);"
        )){
            echo "Error creating table: " . $this->db->errorInfo();
            return;
        }
        $this->enable = true;
    }

    public function isEnable() : bool{
        return $this->enable;
    }

    public function checkDataBase(string $anticheat) : void{
        $stmt = $this->db->prepare("SELECT * FROM vennvac WHERE anticheat = :anticheat");
        $stmt->bindParam(":anticheat", $anticheat);
        $stmt->execute();
        $result = $stmt->fetch();
        if($result === false){
            $stmt = $this->db->prepare("
                INSERT INTO vennvac(anticheat, haspunished)
                VALUES (:anticheat, :haspunished);"
            );
            $stmt->bindParam(":anticheat", $anticheat);
            $stmt->bindParam(":haspunished", "empty data");
            $stmt->execute();
        }
    }

    public function getHasPunished() : string{
        $stmt = $this->db->prepare("SELECT haspunished FROM vennvac WHERE anticheat=:anticheat");
        $stmt->execute(['anticheat' => "vennvac"]);
        $res = $stmt->fetch();
        $stmt->closeCursor();
        return $res['haspunished'] ?? false;
    }

    public function addHasPunished(string $data) : string{
        $lastData = explode(",", $this->getHasPunished());
        $lastData[] = $data;
        $implode = implode(",", $lastData);
        $stmt = $this->db->prepare("UPDATE vennvac SET haspunished = :implode WHERE anticheat=:anticheat");
        $stmt->execute(['anticheat' => "vennvac", 'implode' => $implode]);
        $stmt->closeCursor();
        return $implode;
    }

    public function removeHasPunished(string $data) : string{
        $lastData = explode(",", $this->getHasPunished());
        $lastData = array_diff($lastData, [$data]);
        $implode = implode(",", $lastData);
        $stmt = $this->db->prepare("UPDATE vennvac SET haspunished = :implode WHERE anticheat=:anticheat");
        $stmt->execute(['anticheat' => "vennvac", 'implode' => $implode]);
        $stmt->closeCursor();
        return $implode;
    }

    public function close() : void{
        $this->db = null;
    }

    public function query(string $query, array $params = []) : \PDOStatement{
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    public function getDatabase() : \PDO{
        return $this->db;
    }
}