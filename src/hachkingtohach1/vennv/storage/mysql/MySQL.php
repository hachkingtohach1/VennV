<?php

namespace hachkingtohach1\vennv\storage\mysql;

use hachkingtohach1\vennv\storage\StorageEngine;

class MySQL extends StorageEngine{

    private bool $enable = false;
    private \mysqli $db;

    public function init() : void{
        $this->db = new \mysqli(
			$this->getConfig()->getData(self::DATABASE_MYSQL_HOST) ?? "127.0.0.1",
			$this->getConfig()->getData(self::DATABASE_MYSQL_AUTH_USERNAME) ?? "root",
			$this->getConfig()->getData(self::DATABASE_MYSQL_AUTH_PASSWORD) ?? "",
			$this->getConfig()->getData(self::DATABASE_MYSQL_DATABASE_NAME) ?? "vennvac",
			$this->getConfig()->getData(self::DATABASE_MYSQL_PORT) ?? 3306
		);
        if($this->db->connect_error){
			echo "Can't connect to MySQL server: ".$this->db->connect_error;
            return;
		}
        if(!$this->db->query("
            CREATE TABLE if NOT EXISTS vennvac(anticheat VARCHAR(50) PRIMARY KEY, haspunished LONGTEXT);"
        )){
            echo "Error creating table: " . $this->db->error;
            return;
        }
        $this->enable = true;
    }

    public function isEnable() : bool{
        return $this->enable;
    }

    public function checkDataBase(string $anticheat = "vennvac") : void{
        $stmt = $this->db->prepare("SELECT * FROM vennvac WHERE anticheat = ?");
        $stmt->bind_param("s", $anticheat);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result === false){
            $stmt = $this->db->prepare("
                INSERT INTO vennvac(anticheat, haspunished)
                VALUES (?, ?);"
            );
            $stmt->bind_param("ss", $anticheat, "empty data");
            $stmt->execute();
        }
    }

    public function getHasPunished() : string{
        $stmt = $this->db->prepare("SELECT haspunished FROM vennvac WHERE anticheat=?");
        $stmt->bind_param("s", "vennvac");
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res['haspunished'] ?? false;
    }

    public function addHasPunished(string $data) : void{
        $lastData = explode(",", $this->getHasPunished());
        $lastData[] = $data;
        $implode = implode(",", $lastData);
        $stmt = $this->db->prepare("UPDATE vennvac SET haspunished = ? WHERE anticheat=?");
        $stmt->bind_param("ss", $implode, "vennvac");
        $stmt->execute();
    }

    public function removeHasPunished(string $data) : void{
        $lastData = explode(",", $this->getHasPunished());
        $lastData = array_diff($lastData, [$data]);
        $implode = implode(",", $lastData);
        $stmt = $this->db->prepare("UPDATE vennvac SET haspunished = ? WHERE anticheat=?");
        $stmt->bind_param("ss", $implode, "vennvac");
        $stmt->execute();
    }

    public function close() : void{
        $this->db = null;
    }

    public function query(string $query) : void{
        $this->db->query($query);
    }

    public function getDatabase() : \mysqli{
        return $this->db;
    }
}