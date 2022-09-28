<?php

namespace hachkingtohach1\vennv\compat;

final class PacketPool{

    private array $packets = [];

    public function add(VPacket $packet) : void{
        $packets[] = $packet;
    }

    public function send() : void{
        foreach($this->packets as $packet){
            PacketManager::getInstance()->broadcastPackets($packet, $packet->origin);
        }
    }

    public function clear() : void{
        $this->packets = [];
    }

    public function get() : array{
        return $this->packets;
    }

    public function count() : int{
        return count($this->packets);
    }
}