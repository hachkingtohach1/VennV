<?php

namespace hachkingtohach1\vennv\compat;

class PacketHandler{

    private static array $handle = [];

    public static function getInstance() : PacketHandler{
        return new self;
    }   

    public function add(string $id, mixed $data) : void{
        self::$handle[$id] = $data;
    }

    public function remove(string $id) : void{
        unset(self::$handle[$id]);
    }

    public function get(string $id) :mixed{
        return self::$handle[$id];
    }

    public function getAll() : array{
        return self::$handle;
    }

    public function clear() : void{
        self::$handle = [];
    }

    public function broadcastPackets(VPacket $packet, string $name) : void{
        $id = rand(1, 50).$name.microtime(true);
        $this->add($id, $packet);
    }
}